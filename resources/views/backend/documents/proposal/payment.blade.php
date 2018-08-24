<div class="proposal-create">
    <div class="row">
        <div class="col-md-12">
            <h5 class="modal-title" id="exampleModalLongTitle"></h5>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-primary">
                <div class="panel-body">
                    <div class="row">
                        <div class="col-xs-12 col-md-12" style="padding-left:25px;">
                            @include('backend.documents.progress-bar-step4')
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12"><hr></div>
                        <div class="col-md-12" style="padding-left:62px; padding-right:62px; padding-bottom:15px;"><h4>Select Payment Typ</h4></div>
                        <div class="col-md-4" style="padding-left:62px; padding-right:62px; padding-bottom:15px;">
                            <div class="form-group">
                                <select class="form-control" name="paymenttyp" id="paymenttyp">
                                    <option value="0">select</option>
                                    <option value="1">Single Deposit</option>
                                    <option value="2">Milestone Plan</option>
                                    <option value="3">On Invoice</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-12 milestonepay" style="padding-left:62px; padding-right:62px; padding-bottom:15px; display:none;"><h4>Setup Milestone Plan</h4></div>
                        <div class="col-md-12 milestonepay" style="padding-left:62px; padding-right:62px; padding-bottom:15px; display:none;">

                            <form>
                                <div class="row">
                                <div class="col-md-3"> <input type="text" id="name" placeholder="Name" class="form-control"></div>
                                <div class="col-md-3"> <input type="text" id="percentages" placeholder="Percentages" class="form-control"></div>
                                <div class="col-md-6"> <input type="button" class="add-row btn btn-orange" value="Add Row">  <button type="button" class="delete-row btn btn-orange">Delete Row</button></div>
                                </div>
                            </form>
                            <div class="row">
                                <div class="col-md-12" style="padding-top:25px;">
                                <table>
                                    <thead>
                                    <tr>
                                        <th style="width:100px;">Select</th>
                                        <th style="width:200px;">Name</th>
                                        <th style="width:200px;">Percentages</th>
                                    </tr>
                                    <tr>
                                        <td colspan="3" style="width:100%;"> <hr></td>
                                    </tr>
                                    </thead>
                                    <tbody>

                                    </tbody>
                                </table>
                                </div>
                            </div>

                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12" style="padding-left:62px; padding-right:62px; padding-bottom:15px; text-align: right; float:right;">
                            <button type="submit" class="btn btn-orange create-offer"disabled id="save-proposal-blank">Create your offer</button>
                            <button type="submit" class="btn btn-orange send-offer" disabled id="save-proposal-blank">Send your offer</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>