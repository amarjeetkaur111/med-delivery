 
@include('Includes.Header')  
  @if($errors->AddAdmin->any())
    <script>
       $(function(){
           $('#add_admin_modal').modal('show');
       });
    </script>
  @enderror
  @if($errors->EditAdmin->any())
    <script>
       $(function(){
           $('#edit_admin_modal').modal('show');
       });
    </script>
  @enderror
  @if($errors->AddPharmacy->any())
    <script>
       $(function(){
           $('#add_pharmacist_modal').modal('show');
       });
    </script>
  @enderror
  @if($errors->EditPharmacist->any())
    <script>
       $(function(){
           $('#edit_pharmacist_modal').modal('show');
       });
    </script>
  @enderror
  @if($errors->AddDriver->any())
    <script>
       $(function(){
           $('#add_driver_modal').modal('show');
       });
    </script>
  @enderror
  @if($errors->EditDriver->any())
    <script>
       $(function(){
           $('#edit_driver_modal').modal('show');
       });
    </script>
  @enderror

  <!-- Main Navigation  -->
  <!-- Main layout  -->
  <main class="user-list">
    <div class="container-fluid mb-5">
      <!-- Section: Basic examples -->
      <section>
        <!-- Gird column -->
        <div class="col-md-12">
          <h5 class="my-4 dark-grey-text font-weight-bold"></h5>
         <!-- Nav tabs -->
          <ul class="nav nav-tabs md-tabs nav-justified primary-color" id="myTab" role="tablist">
            <li class="nav-item">
              <a class="nav-link active" id="admin-tab" data-toggle="tab" href="#admin" role="tab" aria-controls="admin" aria-selected="true">Admins</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" id="pharmacist-tab" data-toggle="tab" href="#pharmacist" role="tab" aria-controls="pharmacist" aria-selected="false">Pharmacists</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" id="driver-tab" data-toggle="tab" href="#driver" role="tab" aria-controls="driver" aria-selected="false">Drivers</a>
            </li>            
          </ul>
         <!-- Nav tabs Ends -->

          <!-- Tab panes -->
          <div class="tab-content">
            <div class="tab-pane active" id="admin" role="tabpanel" aria-labelledby="admin-tab">
              <div class="card">
                <div class="card-body">
                  <div class="row">
                    <div class="col-md-6"><h4>Admin Users</h4>
                    </div>
                    <div class="col-md-6 text-right">
                      <button type="button" class="btn btn-outline-primary waves-effect" data-toggle="modal" data-target="#add_admin_modal" >Add Admin</button>
                      </a>
                    </div>
                  </div>
                  <hr>
                  <table id="customer_table" class="table table-striped" cellspacing="0" width="100%">
                    <thead>
                      <tr>
                        <th>Id</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Phone Number</th>
                        <th>Edit</th>
                        <th>Delete</th>
                      </tr>
                    </thead>
                    <tbody>
                      @foreach($userdata as $data)
                      @if($data->UserType == 'Admin')
                      <tr>
                        <td>{{$data->Id}}</td>
                        <td>{{$data->FirstName}} {{$data->LastName}}</td>
                        <td>{{$data->Email}}</td>
                        <td>{{$data->PhoneNumber}}</td>                    
                        <!-- <td></td> -->
                        <td><a href="" id="edit_admin" data-toggle="modal" data-target='#edit_admin_modal' data-id="{{encval($data->Id)}}"><i class="icon-item fas fa-user-edit" role="button" data-prefix="fas" data-id="" data-unicode="f4ff" data-mdb-original-title="" title=""></i></a></td>

                        <td><a href="javascript:void(0);" data-name="{{$data->FirstName}} {{$data->LastName}}" data-id="{{encval($data->Id)}}" id="deleteadmin"><i class="icon-item fas fa-trash-alt" role="button" data-prefix="fas" data-id="trash-alt" data-unicode="f2ed" data-mdb-original-title="" title=""></i></a></td>
                      </tr> 
                      @endif  
                      @endforeach               
                    </tbody>
                    
                  </table>
                </div>
              </div>
            </div>
            <div class="tab-pane" id="pharmacist" role="tabpanel" aria-labelledby="pharmacist-tab">
              <div class="card">
                <div class="card-body">
                  <div class="row">
                    <div class="col-md-6"><h4>Pharmacist Users</h4>
                    </div>
                    <div class="col-md-6 text-right">
                      <button type="button" class="btn btn-outline-primary waves-effect" data-toggle="modal" data-target="#add_pharmacist_modal" >Add Pharmacist</button>
                    </div>
                  </div>
                  <hr>
                  <table id="customer_table" class="table table-striped" cellspacing="0" width="100%">
                    <thead>
                      <tr>
                        <th>Id</th>
                        <th>Pharmacy</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Phone Number</th>
                        <th>Edit</th>
                        <th>Delete</th>
                      </tr>
                    </thead>
                    <tbody>
                      @foreach($pharmacyuser as $data)
                      <tr>
                        <td>{{$data->User->Id}}</td>
                        <td>{{$data->Pharmacy->PharmacyName}}</td>
                        <td>{{$data->User->FirstName}} {{$data->User->LastName}}</td>
                        <td>{{$data->User->Email}}</td>
                        <td>{{$data->User->PhoneNumber}}</td>                    
                        <!-- <td></td> -->
                         <td><a href="" id="edit_pharmacist" data-toggle="modal" data-target='#edit_pharmacist_modal' data-id="{{encval($data->User->Id)}}"><i class="icon-item fas fa-user-edit" role="button" data-prefix="fas" data-id="" data-unicode="f4ff" data-mdb-original-title="" title=""></i></a></td>

                        <td><a href="javascript:void(0);" data-name="{{$data->User->FirstName}} {{$data->User->LastName}}" data-id="{{encval($data->User->Id)}}" id="deletepharmacist"><i class="icon-item fas fa-trash-alt" role="button" data-prefix="fas" data-id="trash-alt" data-unicode="f2ed" data-mdb-original-title="" title=""></i></a></td>
                      </tr>  
                      @endforeach               
                    </tbody>
                    
                  </table>
                </div>
              </div>
            </div>
            <div class="tab-pane" id="driver" role="tabpanel" aria-labelledby="driver-tab">
              <div class="card">
                <div class="card-body">
                  <div class="row">
                    <div class="col-md-6"><h4>Driver Users</h4>
                    </div>
                    <div class="col-md-6 text-right">
                      <button type="button" class="btn btn-outline-primary waves-effect" data-toggle="modal" data-target="#add_driver_modal" >Add Driver</button>
                    </div>
                  </div>
                  <hr>
                  <table id="customer_table" class="table table-striped" cellspacing="0" width="100%">
                    <thead>
                      <tr>
                        <th>Id</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Phone Number</th>
                        <th>Edit</th>
                        <th>Delete</th>
                      </tr>
                    </thead>
                    <tbody>
                      @foreach($userdata as $data)
                      @if($data->UserType == 'Driver')
                      <tr>
                        <td>{{$data->Id}}</td>
                        <td>{{$data->FirstName}} {{$data->LastName}}</td>
                        <td>{{$data->Email}}</td>
                        <td>{{$data->PhoneNumber}}</td>                    
                        <!-- <td></td> -->
                         <td><a href="" id="edit_driver" data-toggle="modal" data-target='#edit_driver_modal' data-id="{{encval($data->Id)}}"><i class="icon-item fas fa-user-edit" role="button" data-prefix="fas" data-id="" data-unicode="f4ff" data-mdb-original-title="" title=""></i></a></td>

                        <td><a href="javascript:void(0);" data-name="{{$data->FirstName}} {{$data->LastName}}" data-id="{{encval($data->Id)}}" id="deletedriver"><i class="icon-item fas fa-trash-alt" role="button" data-prefix="fas" data-id="trash-alt" data-unicode="f2ed" data-mdb-original-title="" title=""></i></a></td>
                      </tr> 
                      @endif  
                      @endforeach               
                    </tbody>
                    
                  </table>
                </div>
              </div>
            </div>
          </div>

         <!-- Tab Panes Ends -->

         <!-- Add Admin Modal -->

        <div class="modal fade" id="add_admin_modal" tabindex="-1" role="dialog" aria-labelledby="ModalLabel" aria-hidden="true">
          <div class="modal-dialog" role="document">
            <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title" id="ModalLabel">Add New Admin</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
              </div>
              <div class="modal-body">
                <form method="post" action="{{route('AddUser')}}">
                  @csrf
                  <input type="hidden" class="form-control" name="UserType" autofocus value="Admin">
                  <div class="row">
                    <!-- Grid column -->
                    <div class="col-md-10 offset-1">
                      <div class="md-form md-outline">
                        <input type="text" class="form-control" id="FirstName" name="FirstName" autofocus value="{{old('FirstName')}}" style="height: 3rem;">
                        <label for="FirstName" class="">First Name</label>
                        @if($errors->AddAdmin->has('FirstName'))
                          <span class="text-danger">{{$errors->AddAdmin->first('FirstName')}}</span>
                        @endif
                      </div>
                      <div class="md-form md-outline">
                        <input type="text" class="form-control" id="LastName" name="LastName" autofocus value="{{old('LastName')}}" style="height: 3rem;">
                        <label for="LastName" class="">Last Name</label>
                        @if($errors->AddAdmin->has('LastName'))
                          <span class="text-danger">{{$errors->AddAdmin->first('LastName')}}</span>
                        @endif
                      </div>
                      <div class="md-form md-outline">
                        <input type="text" class="form-control" id="Email" name="Email" autofocus value="{{old('Email')}}" style="height: 3rem;">
                        <label for="Email" class="">Email</label>
                        @if($errors->AddAdmin->has('Email'))
                          <span class="text-danger">{{$errors->AddAdmin->first('Email')}}</span>
                        @endif
                      </div>
                       <div class="md-form md-outline">
                        <input type="tel" class="form-control" id="PhoneNumber" name="PhoneNumber" autofocus value="{{old('PhoneNumber')}}" style="height: 3rem;" maxlength="12">
                        <label for="PhoneNumber" class="">Phone Number</label>
                        @if($errors->AddAdmin->has('PhoneNumber'))
                          <span class="text-danger">{{$errors->AddAdmin->first('PhoneNumber')}}</span>
                        @endif
                      </div>
                      <div class="md-form md-outline">
                        <input type="text" class="form-control" id="Password" name="Password" autofocus value="{{old('Password')}}" style="height: 3rem;">
                        <label for="Password" class="">Password</label>
                        @if($errors->AddAdmin->has('Password'))
                          <span class="text-danger">{{$errors->AddAdmin->first('Password')}}</span>
                        @endif
                      </div>
                      <button type="submit" class="btn btn-primary waves-effect waves-light">Save</button>                   
                      <button type="button" class="btn btn-primary waves-effect waves-light" data-dismiss="modal">Cancel</button>
                    </div>
                  </div>
                </form> 
              </div>   
            </div>              
          </div>
        </div>      
        <!--------- Add Admin Model Ends ------------->

        <!-- Edit Admin Modal -->

        <div class="modal fade" id="edit_admin_modal" tabindex="-1" role="dialog" aria-labelledby="ModalLabel" aria-hidden="true">
          <div class="modal-dialog" role="document">
            <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title" id="ModalLabel">Edit Admin</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
              </div>
              <div class="modal-body">
                <form method="post" action="{{route('UpdateUser')}}">
                  @csrf
                  <input type="hidden" class="form-control" name="UserType" autofocus value="Admin">
                  <input type="hidden" class="form-control" id="EditId" name="EditId" autofocus value="{{old('EditId')}}">
                  <div class="row">
                    <!-- Grid column -->
                    <div class="col-md-10 offset-1">
                      <div class="md-form md-outline">
                        <input type="text" class="form-control" id="EditAdminFirstName" name="EditAdminFirstName" autofocus value="{{old('EditAdminFirstName')}}" style="height: 3rem;">
                        <label for="EditAdminFirstName" class="">First Name</label>
                        @if($errors->EditAdmin->has('EditAdminFirstName'))
                          <span class="text-danger">{{$errors->EditAdmin->first('EditAdminFirstName')}}</span>
                        @endif
                      </div>
                      <div class="md-form md-outline">
                        <input type="text" class="form-control" id="EditAdminLastName" name="EditAdminLastName" autofocus value="{{old('EditAdminLastName')}}" style="height: 3rem;">
                        <label for="EditAdminLastName" class="">Last Name</label>
                        @if($errors->EditAdmin->has('EditAdminLastName'))
                          <span class="text-danger">{{$errors->EditAdmin->first('EditAdminLastName')}}</span>
                        @endif
                      </div>
                      <div class="md-form md-outline">
                        <input type="text" class="form-control" id="EditAdminEmail" name="EditAdminEmail" autofocus value="{{old('EditAdminEmail')}}" style="height: 3rem;">
                        <label for="EditAdminEmail" class="">Email</label>
                        @if($errors->EditAdmin->has('EditAdminEmail'))
                          <span class="text-danger">{{$errors->EditAdmin->first('EditAdminEmail')}}</span>
                        @endif
                      </div>
                       <div class="md-form md-outline">
                        <input type="tel" class="form-control" id="EditAdminPhoneNumber" name="EditAdminPhoneNumber" autofocus value="{{old('EditAdminPhoneNumber')}}" style="height: 3rem;" maxlength="12">
                        <label for="EditAdminPhoneNumber" class="">Phone Number</label>
                        @if($errors->EditAdmin->has('EditAdminPhoneNumber'))
                          <span class="text-danger">{{$errors->EditAdmin->first('EditAdminPhoneNumber')}}</span>
                        @endif
                      </div>
                      <div class="md-form md-outline">
                        <input type="text" class="form-control" id="EditAdminPassword" name="EditAdminPassword" autofocus value="{{old('EditAdminPassword')}}" style="height: 3rem;">
                        <label for="EditAdminPassword" class="">Password</label>                        
                      </div>
                      <button type="submit" class="btn btn-primary waves-effect waves-light">Save</button>                   
                      <button type="button" class="btn btn-primary waves-effect waves-light" data-dismiss="modal">Cancel</button>
                    </div>
                  </div>
                </form> 
              </div>   
            </div>              
          </div>
        </div>
        
        <!--------- Edit Admin Model Ends ------------->

        <!------------- Add Pharmacist Modal -------------->

        <div class="modal fade" id="add_pharmacist_modal" tabindex="-1" role="dialog" aria-labelledby="ModalLabel" aria-hidden="true">
          <div class="modal-dialog" role="document">
            <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title" id="ModalLabel">Add New Pharmacist</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
              </div>
              <div class="modal-body">
                <form method="post" action="{{route('AddUser')}}">
                  @csrf
                  <input type="hidden" class="form-control" name="UserType" autofocus value="Pharmacist">
                  <div class="row">
                    <!-- Grid column -->
                    <div class="col-md-10 offset-1">
                      <div class="md-form md-outline">
                       <select class="form-control form-control-sm" name="PharmacyId" id="PharmacyId" style="height: 2.5rem;">
                          <option value="">Select Pharmacy</option>
                        @foreach($pharmacydata as $id)                         
                          <option value="{{old('PharmacyId',$id->PharmacyId)}}">{{$id->PharmacyName}}</option>
                        @endforeach
                        </select>
                        @if($errors->AddPharmacy->has('PharmacyId'))
                          <span class="text-danger">{{$errors->AddPharmacy->first('PharmacyId')}}</span>
                        @endif
                      </div>
                      <div class="md-form md-outline">
                        <input type="text" class="form-control" id="AddPharmacistFirstName" name="AddPharmacistFirstName" autofocus value="{{old('AddPharmacistFirstName')}}" style="height: 3rem;">
                        <label for="AddPharmacistFirstName" class="">First Name</label>
                        @if($errors->AddPharmacy->has('AddPharmacistFirstName'))
                          <span class="text-danger">{{$errors->AddPharmacy->first('AddPharmacistFirstName')}}</span>
                        @endif
                      </div>
                      <div class="md-form md-outline">
                        <input type="text" class="form-control" id="AddPharmacistLastName" name="AddPharmacistLastName" autofocus value="{{old('AddPharmacistLastName')}}" style="height: 3rem;">
                        <label for="AddPharmacistLastName" class="">Last Name</label>
                        @if($errors->AddPharmacy->has('AddPharmacistLastName'))
                          <span class="text-danger">{{$errors->AddPharmacy->first('AddPharmacistLastName')}}</span>
                        @endif
                      </div>
                      <div class="md-form md-outline">
                        <input type="text" class="form-control" id="AddPharmacistEmail" name="AddPharmacistEmail" autofocus value="{{old('AddPharmacistEmail')}}" style="height: 3rem;">
                        <label for="AddPharmacistEmail" class="">Email</label>
                        @if($errors->AddPharmacy->has('AddPharmacistEmail'))
                          <span class="text-danger">{{$errors->AddPharmacy->first('AddPharmacistEmail')}}</span>
                        @endif
                      </div>
                       <div class="md-form md-outline">
                        <input type="tel" class="form-control" id="AddPharmacistPhoneNumber" name="AddPharmacistPhoneNumber" autofocus value="{{old('AddPharmacistPhoneNumber')}}" style="height: 3rem;" maxlength="12">
                        <label for="AddPharmacistPhoneNumber" class="">Phone Number</label>
                        @if($errors->AddPharmacy->has('AddPharmacistPhoneNumber'))
                          <span class="text-danger">{{$errors->AddPharmacy->first('AddPharmacistPhoneNumber')}}</span>
                        @endif
                      </div>
                      <div class="md-form md-outline">
                        <input type="text" class="form-control" id="AddPharmacistPassword" name="AddPharmacistPassword" autofocus value="{{old('AddPharmacistPassword')}}" style="height: 3rem;">
                        <label for="AddPharmacistPassword" class="">Password</label>
                        @if($errors->AddPharmacy->has('AddPharmacistPassword'))
                          <span class="text-danger">{{$errors->AddPharmacy->first('AddPharmacistPassword')}}</span>
                        @endif
                      </div>
                      <button type="submit" class="btn btn-primary waves-effect waves-light">Save</button>                   
                      <button type="button" class="btn btn-primary waves-effect waves-light" data-dismiss="modal">Cancel</button>
                    </div>
                  </div>
                </form> 
              </div>   
            </div>              
          </div>
        </div>      
        <!--------- Add Pharmacist Model Ends ------------->

        <!-------------- Edit Pharmacist Modal ---------->

        <div class="modal fade" id="edit_pharmacist_modal" tabindex="-1" role="dialog" aria-labelledby="ModalLabel" aria-hidden="true">
          <div class="modal-dialog" role="document">
            <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title" id="ModalLabel">Edit Pharmacist</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
              </div>
              <div class="modal-body">
                <form method="post" action="{{route('UpdateUser')}}">
                  @csrf
                  <input type="hidden" class="form-control" name="UserType" autofocus value="Pharmacist">
                  <input type="hidden" class="form-control" id="EditPharmacistId" name="EditPharmacistId" autofocus value="{{old('EditPharmacistId')}}">
                  <div class="row">
                    <!-- Grid column -->
                    <div class="col-md-10 offset-1">
                      <div class="md-form md-outline">
                       <select class="form-control form-control-sm" name="EditPharmacyId" id="EditPharmacyId" style="height: 2.5rem;">
                          <option value="">Select Pharmacy</option>
                        @foreach($pharmacydata as $id)                         
                          <option value="{{$id->PharmacyId}}">{{$id->PharmacyName}}</option>
                        @endforeach
                        </select>
                        @if($errors->EditPharmacist->has('EditPharmacyId'))
                          <span class="text-danger">{{$errors->EditPharmacist->first('EditPharmacyId')}}</span>
                        @endif
                      </div>
                      <div class="md-form md-outline">
                        <input type="text" class="form-control" id="EditPharmacistFirstName" name="EditPharmacistFirstName" autofocus value="{{old('EditPharmacistFirstName')}}" style="height: 3rem;">
                        <label for="EditPharmacistFirstName" class="">First Name</label>
                        @if($errors->EditPharmacist->has('EditPharmacistFirstName'))
                          <span class="text-danger">{{$errors->EditPharmacist->first('EditPharmacistFirstName')}}</span>
                        @endif
                      </div>
                      <div class="md-form md-outline">
                        <input type="text" class="form-control" id="EditPharmacistLastName" name="EditPharmacistLastName" autofocus value="{{old('EditPharmacistLastName')}}" style="height: 3rem;">
                        <label for="EditPharmacistLastName" class="">Last Name</label>
                        @if($errors->EditPharmacist->has('EditPharmacistLastName'))
                          <span class="text-danger">{{$errors->EditPharmacist->first('EditPharmacistLastName')}}</span>
                        @endif
                      </div>
                      <div class="md-form md-outline">
                        <input type="text" class="form-control" id="EditPharmacistEmail" name="EditPharmacistEmail" autofocus value="{{old('EditPharmacistEmail')}}" style="height: 3rem;">
                        <label for="EditPharmacistEmail" class="">Email</label>
                        @if($errors->EditPharmacist->has('EditPharmacistEmail'))
                          <span class="text-danger">{{$errors->EditPharmacist->first('EditPharmacistEmail')}}</span>
                        @endif
                      </div>
                       <div class="md-form md-outline">
                        <input type="tel" class="form-control" id="EditPharmacistPhoneNumber" name="EditPharmacistPhoneNumber" autofocus value="{{old('EditPharmacistPhoneNumber')}}" style="height: 3rem;" maxlength="12">
                        <label for="EditPharmacistPhoneNumber" class="">Phone Number</label>
                        @if($errors->EditPharmacist->has('EditPharmacistPhoneNumber'))
                          <span class="text-danger">{{$errors->EditPharmacist->first('EditPharmacistPhoneNumber')}}</span>
                        @endif
                      </div>
                      <div class="md-form md-outline">
                        <input type="text" class="form-control" id="EditPharmacistPassword" name="EditPharmacistPassword" autofocus value="{{old('EditPharmacistPassword')}}" style="height: 3rem;">
                        <label for="EditPharmacistPassword" class="">Password</label>
                      </div>
                      <button type="submit" class="btn btn-primary waves-effect waves-light">Save</button>                   
                      <button type="button" class="btn btn-primary waves-effect waves-light" data-dismiss="modal">Cancel</button>
                    </div>
                  </div>
                </form> 
              </div>   
            </div>              
          </div>
        </div>
        
        <!--------- Edit Pharmacist Model Ends ------------->

        <!------------- Add Driver Modal -------------->

        <div class="modal fade" id="add_driver_modal" tabindex="-1" role="dialog" aria-labelledby="ModalLabel" aria-hidden="true">
          <div class="modal-dialog" role="document">
            <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title" id="ModalLabel">Add New Driver</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
              </div>
              <div class="modal-body">
                <form method="post" action="{{route('AddUser')}}">
                  @csrf
                  <input type="hidden" class="form-control" name="UserType" autofocus value="Driver">
                  <div class="row">
                    <!-- Grid column -->
                    <div class="col-md-10 offset-1">
                      <div class="md-form md-outline">
                        <input type="text" class="form-control" id="AddDriverFirstName" name="AddDriverFirstName" autofocus value="{{old('AddDriverFirstName')}}" style="height: 3rem;">
                        <label for="AddDriverFirstName" class="">First Name</label>
                        @if($errors->AddDriver->has('AddDriverFirstName'))
                          <span class="text-danger">{{$errors->AddDriver->first('AddDriverFirstName')}}</span>
                        @endif
                      </div>
                      <div class="md-form md-outline">
                        <input type="text" class="form-control" id="AddDriverLastName" name="AddDriverLastName" autofocus value="{{old('AddDriverLastName')}}" style="height: 3rem;">
                        <label for="AddDriverLastName" class="">Last Name</label>
                       @if($errors->AddDriver->has('AddDriverLastName'))
                          <span class="text-danger">{{$errors->AddDriver->first('AddDriverLastName')}}</span>
                        @endif
                      </div>
                      <div class="md-form md-outline">
                        <input type="text" class="form-control" id="AddDriverEmail" name="AddDriverEmail" autofocus value="{{old('AddDriverEmail')}}" style="height: 3rem;">
                        <label for="AddDriverEmail" class="">Email</label>
                        @if($errors->AddDriver->has('AddDriverEmail'))
                          <span class="text-danger">{{$errors->AddDriver->first('AddDriverEmail')}}</span>
                        @endif
                      </div>
                       <div class="md-form md-outline">
                        <input type="tel" class="form-control" id="AddDriverPhoneNumber" name="AddDriverPhoneNumber" autofocus value="{{old('AddDriverPhoneNumber')}}" style="height: 3rem;" maxlength="12">
                        <label for="AddDriverPhoneNumber" class="">Phone Number</label>
                        @if($errors->AddDriver->has('AddDriverPhoneNumber'))
                          <span class="text-danger">{{$errors->AddDriver->first('AddDriverPhoneNumber')}}</span>
                        @endif
                      </div>
                      <div class="md-form md-outline">
                        <input type="text" class="form-control" id="AddDriverPassword" name="AddDriverPassword" autofocus value="{{old('AddDriverPassword')}}" style="height: 3rem;">
                        <label for="AddDriverPassword" class="">Password</label>
                        @if($errors->AddDriver->has('AddDriverPassword'))
                          <span class="text-danger">{{$errors->AddDriver->first('AddDriverPassword')}}</span>
                        @endif
                      </div>
                      <button type="submit" class="btn btn-primary waves-effect waves-light">Save</button>                   
                      <button type="button" class="btn btn-primary waves-effect waves-light" data-dismiss="modal">Cancel</button>
                    </div>
                  </div>
                </form> 
              </div>   
            </div>              
          </div>
        </div>      
        <!--------- Add Driver Model Ends ------------->

        <!-------------- Edit Driver Modal ---------->

        <div class="modal fade" id="edit_driver_modal" tabindex="-1" role="dialog" aria-labelledby="ModalLabel" aria-hidden="true">
          <div class="modal-dialog" role="document">
            <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title" id="ModalLabel">Edit Driver</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
              </div>
              <div class="modal-body">
                <form method="post" action="{{route('UpdateUser')}}">
                  @csrf
                  <input type="hidden" class="form-control" name="UserType" autofocus value="Driver">
                  <input type="hidden" class="form-control" id="EditDriverId" name="EditDriverId" autofocus value="{{old('EditDriverId')}}">
                  <div class="row">
                    <!-- Grid column -->
                    <div class="col-md-10 offset-1">
                      <div class="md-form md-outline">
                        <input type="text" class="form-control" id="EditDriverFirstName" name="EditDriverFirstName" autofocus value="{{old('EditDriverFirstName')}}" style="height: 3rem;">
                        <label for="EditDriverFirstName" class="">First Name</label>
                        @if($errors->EditDriver->has('EditDriverFirstName'))
                          <span class="text-danger">{{$errors->EditDriver->first('EditDriverFirstName')}}</span>
                        @endif
                      </div>
                      <div class="md-form md-outline">
                        <input type="text" class="form-control" id="EditDriverLastName" name="EditDriverLastName" autofocus value="{{old('EditDriverLastName')}}" style="height: 3rem;">
                        <label for="EditDriverLastName" class="">Last Name</label>
                        @if($errors->EditDriver->has('EditDriverLastName'))
                          <span class="text-danger">{{$errors->EditDriver->first('EditDriverLastName')}}</span>
                        @endif
                      </div>
                      <div class="md-form md-outline">
                        <input type="text" class="form-control" id="EditDriverEmail" name="EditDriverEmail" autofocus value="{{old('EditDriverEmail')}}" style="height: 3rem;">
                        <label for="EditDriverEmail" class="">Email</label>
                        @if($errors->EditDriver->has('EditDriverEmail'))
                          <span class="text-danger">{{$errors->EditDriver->first('EditDriverEmail')}}</span>
                        @endif
                      </div>
                       <div class="md-form md-outline">
                        <input type="tel" class="form-control" id="EditDriverPhoneNumber" name="EditDriverPhoneNumber" autofocus value="{{old('EditDriverPhoneNumber')}}" style="height: 3rem;" maxlength="12">
                        <label for="EditDriverPhoneNumber" class="">Phone Number</label>
                        @if($errors->EditDriver->has('EditDriverPhoneNumber'))
                          <span class="text-danger">{{$errors->EditDriver->first('EditDriverPhoneNumber')}}</span>
                        @endif
                      </div>
                      <div class="md-form md-outline">
                        <input type="text" class="form-control" id="EditDriverPassword" name="EditDriverPassword" autofocus value="{{old('EditDriverPassword')}}" style="height: 3rem;">
                        <label for="EditDriverPassword" class="">Password</label>
                      </div>
                      <button type="submit" class="btn btn-primary waves-effect waves-light">Save</button>                   
                      <button type="button" class="btn btn-primary waves-effect waves-light" data-dismiss="modal">Cancel</button>
                    </div>
                  </div>
                </form> 
              </div>   
            </div>              
          </div>
        </div>
        
        <!--------- Edit Driver Model Ends ------------->

         

        </div>
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

