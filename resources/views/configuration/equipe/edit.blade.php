<!-- Large Size -->
<div class="modal fade" id="Update_equipe" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="title" id="titeupdate"></h4>
            </div>
            <form action="{{ url('update/equipes/updateequipes') }}" method="POST" id="updateequipes">
                @csrf
                <div class="modal-body">
                    <div class="row">
                        <input type="hidden" name="id" id="equipeid">
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label>Departement</label>
                                <input placeholder="Departement" id="elabel" name="label" type="text" class="form-control" required="">
                            </div>
                        </div>
                        <div class="col-lg-6 col-md-6 col-sm-12">
                            <div class="form-group">
                                <label for="duniteid" class="control-label">Unité</label>
                                <select name="uniteid" id="euniteid" class="form-control">
                                    <option value="">-DEOULER-</option>
                                    @foreach($unites as $liste)
                                        <option value="{{ $liste->id }}">{{ $liste->label }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label>Chef d'equipe</label>
                                <select name="chefEquipeid" id="echefEquipeid" class="form-control" data-placeholder="Select">
                                    <option value="">-DEROULER-</option>
                                    @foreach($unites as $liste)
                                        <option value="{{ $liste->id }}">{{ $liste->label }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-sm-12">
                            <div class="form-group">
                                <label>Description du departement</label>
                                <textarea placeholder="Description du departement" class="form-control" id="edescription" name="description" rows="4"></textarea>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Modifier</button>
                    <button type="button" class="btn btn-danger" data-dismiss="modal">Fermer</button>
                </div>
            </form>
        </div>
    </div>
</div>