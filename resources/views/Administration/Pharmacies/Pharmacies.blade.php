 @include('Includes.Header')
@if($errors->AddPharmacy->any())
    <script>
       $(function(){
           $('#add_pharmacy_modal').modal('show');
       });
    </script>
  @enderror
  @if($errors->EditPharmacy->any())
    <script>
       $(function(){
           $('#edit_pharmacy_modal').modal('show');
       });
    </script>
  @enderror
  <!-- Main Navigation  -->
  <!-- Main layout  -->
  <main class="pharmacy">
    <div class="customer-list container-fluid mb-5 cont-margin">
      <!-- Section: Basic examples -->
      <section>
        <!-- Gird column -->
        <div class="col-md-12">
          <h5 class="my-4 dark-grey-text font-weight-bold"></h5>
          <div class="row">
          <div class="col-md-6 heading text-left">                   
          </div>
        </div>
          <div class="card">
            <div class="card-body">
              <div class="row">
                <div class="col-md-6"><h4 class="mt-3">Pharmacy List</h4>
                </div>
                <div class="col-md-6">
                  <div class="float-right">
                    <a><button type="button" class="btn btn-outline-primary waves-effect" data-toggle="modal" data-target="#add_pharmacy_modal" >Add Pharmacy</button></a> 
                  </div>
                </div>
              </div>
              <hr>              
              <table id="medication_table" class="table table-striped" cellspacing="0" width="100%">
                <thead>
                  <tr>
                    <th>Id</th>
                    <th>Pharmacy Name</th>                    
                    <th>Address</th>
                    <th>Manager</th>
                    <th>Phone</th>
                    <th>Edit</th>
                    <th>Delete</th>
                  </tr>
                </thead>
                <tbody>  
                @foreach($pharmacy as $data)           
                  <tr>
                    <td>{{$data['PharmacyId']}}</td>
                    <td>{{$data['PharmacyName']}}</td>
                    <td>{{$data['PharmacyAddress']}}</td>
                    <td>{{$data['PharmacyManager']}}</td>
                    <td>{{$data['PharmacyPhone']}}</td>
                    <td><a href="" id="edit_pharmacy" data-toggle="modal" data-target='#edit_pharmacy_modal' data-id="{{encval($data['PharmacyId'])}}"><i class="icon-item fas fa-user-edit" role="button" data-prefix="fas" data-id="" data-unicode="f4ff" data-mdb-original-title="" title=""></i></a></td>
                    <td><a href="javascript:void(0);" data-name="{{$data['PharmacyName']}}" data-id="{{encval($data['PharmacyId'])}}" id="deletepharmacy"><i class="icon-item fas fa-trash-alt" role="button" data-prefix="fas" data-id="trash-alt" data-unicode="f2ed" data-mdb-original-title="" title=""></i></a></td>
                   </tr>    
                  @endforeach 
                </tbody>                
              </table>
            </div>
          </div>
        </div>
        <!-- Add Modal -->

        <div class="modal fade" id="add_pharmacy_modal" tabindex="-1" role="dialog" aria-labelledby="ModalLabel" aria-hidden="true">
          <div class="modal-dialog" role="document">
            <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title" id="ModalLabel">Add New Pharmacy</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
              </div>
              <div class="modal-body">
                <form method="post" action="{{route('AddPharmacy')}}">
                  @csrf
                  <div class="row">
                    <!-- Grid column -->
                    <div class="col-md-10 offset-1">
                      <div class="md-form md-outline">
                        <input type="text" class="form-control" id="PharmacyName" name="PharmacyName" autofocus value="{{old('PharmacyName')}}" style="height: 3rem;">
                        <label for="PharmacyName" class="">Pharmacy Name</label>
                        @if($errors->AddPharmacy->has('PharmacyName'))
                          <span class="text-danger">{{$errors->AddPharmacy->first('PharmacyName')}}</span>
                        @endif
                      </div>
                      <div class="md-form md-outline">
                        <textarea type="text" class="form-control" id="PharmacyAddress" name="PharmacyAddress" rows="5">{{old('PharmacyAddress')}}</textarea>
                        <label for="PharmacyAddress" class="">Pharmacy Address</label>
                        @if($errors->AddPharmacy->has('PharmacyAddress'))
                          <span class="text-danger">{{$errors->AddPharmacy->first('PharmacyAddress')}}</span>
                        @endif
                      </div>
                      <div class="md-form md-outline">
                        <input type="text" class="form-control color-input" id="PharmacyManager" name="PharmacyManager" autofocus value="{{old('PharmacyManager')}}">
                        <label for="PharmacyManager" class="">Pharmacy Manager</label>
                        @if($errors->AddPharmacy->has('PharmacyManager'))
                          <span class="text-danger">{{$errors->AddPharmacy->first('PharmacyManager')}}</span>
                        @endif
                      </div>
                      <div class="md-form md-outline">
                        <input type="tel" class="form-control color-input" id="PharmacyPhone" name="PharmacyPhone" autofocus value="{{old('PharmacyPhone')}}" maxlength="12">
                        <label for="PharmacyPhone" class="">Pharmacy Phone Number</label>
                        @if($errors->AddPharmacy->has('PharmacyPhone'))
                          <span class="text-danger">{{$errors->AddPharmacy->first('PharmacyPhone')}}</span>
                        @endif
                      </div>
                      <button type="button" class="btn btn-primary waves-effect waves-light" data-dismiss="modal">Cancel</button>
                      <button type="submit" class="btn btn-primary waves-effect waves-light">Save</button>
                    </div>
                  </div>
                </form> 
              </div>   
            </div>              
          </div>
        </div>
        </div>
        <!--------- Model Ends ------------->

        <!-- Edit Modal -->
        <div class="modal fade" id="edit_pharmacy_modal" tabindex="-1" role="dialog" aria-labelledby="ModalLabel" aria-hidden="true">
          <div class="modal-dialog" role="document">
            <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title" id="ModalLabel">Edit Pharmacy</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
              </div>
              <div class="modal-body">
                <form method="post" action="{{route('UpdatePharmacy')}}">
                  @csrf
                  <input type="hidden" id="EditPharmacyId" name="EditPharmacyId" value="{{old('EditPharmacyId')}}">
                  <div class="row">
                    <!-- Grid column -->
                    <div class="col-md-10 offset-1">
                      <div class="md-form md-outline">
                        <input type="text" class="form-control" id="EditPharmacyName" name="EditPharmacyName" autofocus value="{{old('EditPharmacyName')}}" style="height: 3rem;">
                        <label for="EditPharmacyName" class="">Pharmacy Name</label>
                        @if($errors->EditPharmacy->has('EditPharmacyName'))
                          <span class="text-danger">{{$errors->EditPharmacy->first('EditPharmacyName')}}</span>
                        @endif
                      </div>
                      <div class="md-form md-outline">
                        <textarea type="text" class="form-control" id="EditPharmacyAddress" name="EditPharmacyAddress" rows="5">{{old('EditPharmacyAddress')}}</textarea>
                        <label id="forEditPharmacyAdd" for="EditPharmacyAddress" class="">Pharmacy Address</label>
                        @if($errors->EditPharmacy->has('EditPharmacyAddress'))
                          <span class="text-danger">{{$errors->EditPharmacy->first('EditPharmacyAddress')}}</span>
                        @endif
                      </div>
                      <div class="md-form md-outline">
                        <input type="text" class="form-control color-input" id="EditPharmacyManager" name="EditPharmacyManager" autofocus value="{{old('EditPharmacyManager')}}">
                        <label for="EditPharmacyManager" class="">Pharmacy Manager</label>
                        @if($errors->EditPharmacy->has('EditPharmacyManager'))
                          <span class="text-danger">{{$errors->EditPharmacy->first('EditPharmacyManager')}}</span>
                        @endif
                      </div>
                      <div class="md-form md-outline">
                        <input type="tel" class="form-control color-input" id="EditPharmacyPhone" name="EditPharmacyPhone" autofocus value="{{old('EditPharmacyPhone')}}" maxlength="12">
                        <label for="EditPharmacyPhone" class="">Pharmacy Phone Number</label>
                        @if($errors->EditPharmacy->has('EditPharmacyPhone'))
                          <span class="text-danger">{{$errors->EditPharmacy->first('EditPharmacyPhone')}}</span>
                        @endif
                      </div>
                      <button type="button" class="btn btn-primary waves-effect waves-light" data-dismiss="modal">Cancel</button>
                      <button type="submit" class="btn btn-primary waves-effect waves-light">Save</button>
                    </div>
                  </div>
                </form> 
              </div>   
            </div>              
          </div>
        </div>
        </div>
        <!--------- Model Ends ------------->
        <!-- Gird column -->

        <!-- Gird column -->
        <!-- Gird column -->

      </section>
      <!-- Section: Basic examples -->

    </div>

  </main>
  <!-- Main layout -->

@include('Includes.Footer')
<script src="{{asset('js/Administration/Administration.js')}}"></script>
<script>
   
</script>
