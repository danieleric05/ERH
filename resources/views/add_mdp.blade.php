<!-- Large Size -->
<div class="modal fade" id="largeModal_mdp" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header text-center">
                <h4 class="title" id="largeModalLabel">Mot de passe oublié</h4>
            </div>
            <form action="{{ url('add/mission/addmission') }}" method="POST" id="addmission">
                <meta name="csrf-token" content="{{ csrf_token() }}">
                <div class="modal-body">

                    <div class="row">

                        <div class="col-sm-12">
                            <div class="form-group">
                                <label>Votre pseudo</label>
                                <input placeholder="Votre pseudo svp" type="text" name="label" class="form-control" required="">
                            </div>
                        </div>

                    </div>

                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Envoyer la demande</button>
                    <button type="button" class="btn btn-danger" data-dismiss="modal">Fermer</button>
                </div>
            </form>
        </div>
    </div>
</div>