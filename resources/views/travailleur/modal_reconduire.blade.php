<!-- Large Size -->
<div class="modal fade" id="addForm_reconduireJournalier" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="title" id="largeModalLabel">Action sur un journalier</h4>
            </div>
            <form action="{{ url('update/reconduire/uptravailleur') }}" method="POST">
                {{csrf_field()}}
                <div class="modal-body">
                    <input type="hidden" name="id" id="travailid">
                    <div class="row">

                        <div class="col-sm-4">
                            <div class="form-group">
                                <label>Effectuer</label>
                                <select name="actionid" class="form-control" data-placeholder="Select">
                                    <option value="">-DEROULER-</option>
                                    <option selected value="6">RECONDUIRE UN JOURNALIER</option>
                                    <option value="7">PAYER LA PRECARITE</option>
                                </select>
                            </div>
                        </div>

                        <div class="col-sm-4">
                            <div class="form-group">
                                <label>Date de reprise</label>
                                <input type="date" value="{{ date('Y-m-d') }}"  name="datechoisit" class="form-control" required="">
                            </div>
                        </div>

                        <div class="col-sm-4">
                            <div class="form-group">
                                <label>Date fin de contrat</label>
                                <input type="date" name="datefin" class="form-control" required="">
                            </div>
                        </div>

                    </div>

                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Enregistrer</button>
                    <button type="button" class="btn btn-danger" data-dismiss="modal">Fermer</button>
                </div>
            </form>
        </div>
    </div>
</div>