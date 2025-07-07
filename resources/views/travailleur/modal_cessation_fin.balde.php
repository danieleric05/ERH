<!-- Large Size -->
<div class="modal fade" id="addForm_declaration_new" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="title" id="largeModalLabel">Effectuer une action</h4>
            </div>
            <form action="{{ url('update/declarations/updatedeclaration') }}" method="POST">
                {{csrf_field()}}
                <div class="modal-body">
                    <input type="hidden" name="id" id="travailleurid">
                    <div class="row">

                        <div class="col-sm-6">
                            <div class="form-group">
                                <label>Effectuer</label>
                                <select required name="actionid" id="actionid" class="form-control" data-placeholder="Select">
                                    <option value="">-DEROULER-</option>
                                    <option selected value="3">CESSATION</option>
                                </select>
                            </div>
                        </div>

                        <div class="col-sm-6">
                            <div class="form-group">
                                <label>Date</label>
                                <input type="date" value="{{ date('Y-m-d') }}"  name="datechoisit" class="form-control" required="">
                            </div>
                        </div>


                        <div class="col-sm-12" style="display: none" id="motif">
                            <div class="form-group">
                                <label>Motif de départ</label>
                                <input type="text" height="80px" name="motif_fin_contrat" class="form-control">
                            </div>
                        </div>

                        <div class="col-sm-12" style="display: none" id="numcnps">
                            <div class="form-group">
                                <label>Numero CNPS</label>
                                <input type="text" maxlength="12" height="80px" name="numcnps" class="form-control">
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
