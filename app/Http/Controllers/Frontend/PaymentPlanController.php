<?php
/**
 * Created by PhpStorm.
 * User: kuehn_000
 * Date: 26.06.2018
 * Time: 14:08
 */

namespace App\Http\Controllers\Frontend;

// Libraries
use App, Redirect, Auth, DB, Input, Validator, Hash, Session;

use App\Http\Controllers\Controller;


use App\DatabaseModels\Clients;
use App\DatabaseModels\Plans;
use App\DatabaseModels\PlanDocs;
use App\DatabaseModels\UsersPaymentPlan;
use App\DatabaseModels\PlansMilestone;
use App\DatabaseModels\MangoPayout;
use App\DatabaseModels\Companies;
use App\DatabaseModels\Users;
use App\Classes\MangoClass;
use App\Classes\MessagesClass;

class PaymentPlanController extends Controller
{

    /**
     * @var \MangoPay\MangoPayApi
     */
    private $mangopay;

    public function __construct(\MangoPay\MangoPayApi $mangopay) {

        $this->mangopay = $mangopay;

    }

    public function index($hash) {

        if(isset($_GET['protect']) && $_GET['protect'] == true){
            $protect = true;
            $login = true;
        }else{
            $login = false;
            $protect = false;
        }

        // needs a complete new methode -> not safe at all

        if(isset($_GET['login'])) {
            $loggedIn = true;
        }else{
            $loggedIn = false;
        }

        if (isset($_GET['transactionId'])) {
            $loggedIn = true;
        }

        $blade["locale"] = App::getLocale();

        //get all plan details for the normal payment plan view
        $query = DB::table('projects_plans');
        $query->join('clients', 'projects_plans.clients_id_fk', '=', 'clients.id');
        $query->where('projects_plans.hash', '=', $hash);
        $query->select('clients.firstname', 'clients.lastname', 'clients.email', 'clients.firstname', 'clients.address1', 'clients.city', 'clients.address2', 'projects_plans.*');
        $plan = $query->first();

        $company = Companies::where("id", "=", $plan->service_provider_fk)
            ->first();

        if($plan->delete ==1){


            return view('frontend.clients.payment-plan-deleted', compact('blade', 'company', 'plan'));

        }else{


            $user = App\DatabaseModels\Users::where("id", "=", $company->users_fk)
                ->first();

            $docs = PlanDocs::where("plan_id_fk", "=", $plan->id)
                ->get();

            $milestone = PlansMilestone::where("projects_plans_id_fk", "=", $plan->id)
                ->first();

            //only if client comes back from mangopay
            if (isset($_GET['transactionId'])) {

                //if client comes from mangopay they will habe an transaction id
                $transactionId = $_GET['transactionId'];

                //get result of the payin made by client
                $mango_obj = new MangoClass($this->mangopay);
                $payinResult = $mango_obj->getPayInCardWeb($transactionId);

                //update the result of the payin in intern DB
                $payIn = App\DatabaseModels\MangoPayin::where("mango_id", "=", $transactionId)
                    ->first();

                $payIn->state = $payinResult->Status;
                $payIn->result_code = $payinResult->ResultCode;
                $payIn->result_message = $payinResult->ResultMessage;
                $payIn->save();

                //if payment was successful update the paystatus of our intern DB
                if ($payinResult->Status == "SUCCEEDED") {
                    $milestone->paystatus = 2;
                    $milestone->save();

                    $msg_obj = new MessagesClass();
                    $result = $msg_obj->payInSucceeded($milestone, $plan, $user);

                }else{

                    Session::flash('error', 'An error has occurred- '.$payinResult->ResultMessage);

                }
            }

            return view('frontend.clients.payment-plan', compact('blade', 'plan', 'user', 'company', 'milestone', 'docs', 'login', 'hash', 'protect', 'loggedIn'));

        }

    }

    public function loadPreview($id) {

        $blade["locale"] = App::getLocale();


        $plan = Plans::where("id", "=", $id)
            ->first();

        return Redirect::to($blade["locale"]."/payment-plan/preview/".$plan->hash)->withInput()->with('success', 'Your payment plan has been created!');

    }

    public function preview($hash) {

        $blade["locale"] = App::getLocale();

        $user = Auth::user();
        $preview = true;

        $query = DB::table('projects_plans');
        $query->join('clients', 'projects_plans.clients_id_fk', '=', 'clients.id');
        $query->where('projects_plans.hash', '=', $hash );
        $query->select('clients.firstname', 'clients.lastname', 'clients.email', 'clients.firstname', 'clients.address1', 'clients.city', 'clients.address2', 'projects_plans.*');
        $plan = $query->first();

        $company = Companies::where("id", "=", $user->service_provider_fk)
            ->first();

        if(!isset($plan)){
            $plan = Plans::where("hash", "=", $hash)
                ->first();
        }

        $milestone = PlansMilestone::where("projects_plans_id_fk", "=", $plan->id)
            ->first();

        return view('frontend.clients.payment-plan', compact('blade', 'plan', 'user', 'company', 'milestone', 'preview'));
    }

    public function payCC($hash) {

        $blade["locale"] = App::getLocale();
      

        $plan = Plans::where("hash", "=", $hash)
            ->first();

        $company = Companies::where("id", "=", $plan->service_provider_fk)
            ->first();

        $milestone = PlansMilestone::where("projects_plans_id_fk", "=", $plan->id)
            ->first();

        $client = Clients::where("id", "=", $plan->clients_id_fk)
            ->first();

        $mango_obj = new MangoClass($this->mangopay);
        $url=   $mango_obj->createTransaction($company, $client, $milestone, $hash);

        if(isset($url->RedirectURL)){
            return Redirect::to($url->RedirectURL);
        }else{
            return redirect()->back()->withInput()->with('error', 'Error. Please try again or contact trustfy.io.');
        }



    }

    public function payBank($hash) {

        $blade["locale"] = App::getLocale();


        $plan = Plans::where("hash", "=", $hash)
            ->first();

        $company = Companies::where("id", "=", $plan->service_provider_fk)
            ->first();

        $milestone = PlansMilestone::where("projects_plans_id_fk", "=", $plan->id)
            ->first();


        return view('frontend.clients.bank-transfer', compact('blade', 'plan', 'user', 'company', 'milestone'));


    }

    public function releaseMilestone($id) {

        $milestone = PlansMilestone::where("id", "=", $id)
            ->first();

        //get all plan details for the normal payment plan view
        $query = DB::table('projects_plans_milestones');
        $query->join('projects_plans', 'projects_plans.id', '=', 'projects_plans_milestones.projects_plans_id_fk');
        $query->join('companies', 'projects_plans.service_provider_fk', '=', 'companies.id');
        $query->join('companies_mangowallets', 'companies.id', '=', 'companies_mangowallets.performer_id_fk');
        $query->join('companies_bank', 'companies.id', '=', 'companies_mangowallets.performer_id_fk');
        $query->where('projects_plans_milestones.id', '=', $id);
        $query->select('companies.mango_id AS author', 'companies_mangowallets.id AS debited_wallet', 'projects_plans.*', 'projects_plans.id AS planId') ;
        $plan = $query->first();

        $user = Users::where("service_provider_fk", "=", $plan->service_provider_fk)
            ->first();

        //get result of the payin made by client
        $mango_obj = new MangoClass($this->mangopay);
        $payOutResult = $mango_obj->createPayOut($milestone);

        $payout = new MangoPayout();
        $payout->status = $payOutResult->Status;
        $payout->company_id_fk = $plan->service_provider_fk;
        $payout->mango_id = $payOutResult->Id;
        $payout->milestone_id_fk = $id;
        $payout->save();

        if($payOutResult->Status == "CREATED"){

            $subject = "Trustfy Payments - Pay-out confirmation";
            $msg_obj = new MessagesClass();
            $msg_obj->payOutCreated($subject, $user->email, $payout, $plan->planId);

            $milestone->paystatus = 3;
            $milestone->save();

        }

        return Redirect::to("/payment-plan/".$plan->hash)->withInput()->with('success', 'PayOut erfolgreich ausgeführt');

    }


    public function setProtection() {

        $ll = App::getLocale();
        $users = new UsersPaymentPlan;
        $users->plan_hash = $_GET["hash"];
        $users->email = $_GET["email"];
        $users->password =  $_GET["password"];
        $users->active = "0";
        $users->role = 4;
        $users->save();

        $plan = Plans::where("hash", "=", $_GET["hash"])
            ->first();

        //set hide to hide the modal which ask for setting up a protection
        $plan->protection = "hide";
        $plan->protection_user = $users->id;
        $plan->save();


        $subject= "Trustfy Payments - Plan Protection";
        $data['content'] = "<h3>Information to your plan protection</h3>Your Plan Protection: <br>".$_GET["email"]."<br> Passwort:".$_GET["password"];

        $msg_obj = new MessagesClass();
        $msg_obj->sendStandardMail($subject, $data, $_GET["email"]);

        $result = self::login();

        if($result){
            return response()->json(['success' => true, 'msg' => 'Logged In']);
        }else{
            return response()->json(['success' => false, 'msg' => 'Unknown Login Data']);
        }

    }

    public function loginPlan() {

       $result = self::login();

        if($result){
            return response()->json(['success' => true, 'msg' => $result]);
        }else{
            return response()->json(['success' => false, 'msg' => 'Unknown Login Data.']);
        }

    }



    public function login() {

        $data = Input::all();

        $user = UsersPaymentPlan::where("plan_hash", "=", $data["hash"])
            ->first();


        if(empty($user)){

            $plan = Plan::where("hash", "=", $data["hash"])
                ->first();

            $plan->protection = "show";
            $plan->save();

            return false;

        }else{

            if(isset($data["password-login"])){
                if($user->email == $data["email-login"] && $user->password == $data["password-login"]){

                    $user->user_hash = Hash::make($data["password"]);
                    $user->save();

                    return true;
                }else{
                    return false;
                }
            }else{
                if($user->email == $data["email"] && $user->password == $data["password"]){

                    $user->user_hash = Hash::make($data["password"]);
                    $user->save();


                    return true;

                    return $user->user_hash;
                }else{
                    return false;
                }
            }

        }

    }



}