<?php
/**
 * Created by PhpStorm.
 * User: kuehn_000
 * Date: 01.08.2018
 * Time: 17:56
 */

namespace App\DatabaseModels;

use Illuminate\Database\Eloquent\Model;

class Users extends Model
{

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'users';
    protected $primaryKey = 'id';
}