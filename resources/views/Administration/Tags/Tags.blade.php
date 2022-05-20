@include('Includes.Header')
@if($errors->AddTag->any())
    <script>
       $(function(){
           $('#add_tag_modal').modal('show');
       });
    </script>
  @enderror
  @if($errors->EditTag->any())
    <script>
       $(function(){
          $('#edit_tag_modal').modal('show');
       });
    </script>
  @enderror
  <!-- Main Navigation  -->
  <!-- Main layout  -->
  <main class="tags">
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
                <div class="col-md-6"><h4 class="mt-3">Tags List</h4>
                </div>
                <div class="col-md-6">
                  <div class="float-right">
                    <a><button type="button" class="btn btn-outline-primary waves-effect" data-toggle="modal" data-target="#add_tag_modal" >Add Tag</button></a>
                  </div>
                </div>
              </div>
              <hr>              
              <table id="medication_table" class="table table-striped" cellspacing="0" width="100%">
                <thead>
                  <tr>
                    <th>Tag Name</th>
                    <th>Tag Description</th>                    
                    <th>Tag Color</th>
                    <th>Edit</th>
                    <th>Delete</th>
                  </tr>
                </thead>
                <tbody>  
                @foreach($data as $data)           
                  <tr>
                    <td>{{$data['TagName']}}</td>
                    <td>{{$data['TagDescription']}}</td>
                    <td><span class="badge" style="background-color:{{$data['TagColor']}}">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span></td>
                    <td><a href="" id="edit_tag" data-toggle="modal" data-target='#edit_tag_modal' data-id="{{encval($data['TagId'])}}"><i class="icon-item fas fa-user-edit" role="button" data-prefix="fas" data-id="" data-unicode="f4ff" data-mdb-original-title="" title=""></i></a></td>

                    <td><a href="javascript:void(0);" data-name="{{$data['TagName']}}" data-id="{{encval($data['TagId'])}}" id="deletetag"><i class="icon-item fas fa-trash-alt" role="button" data-prefix="fas" data-id="trash-alt" data-unicode="f2ed" data-mdb-original-title="" title=""></i></a></td>
                   </tr>    
                  @endforeach 
                </tbody>                
              </table>
            </div>
          </div>
        </div>
        <!-- Add Modal -->

        <div class="modal fade" id="add_tag_modal" tabindex="-1" role="dialog" aria-labelledby="ModalLabel" aria-hidden="true">
          <div class="modal-dialog" role="document">
            <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title" id="ModalLabel">Add New Tag</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
              </div>
              <div class="modal-body">
                <form method="post" action="{{route('AddTag')}}">
                  @csrf
                  <div class="row">
                    <!-- Grid column -->
                    <div class="col-md-10 offset-1">
                      <div class="md-form md-outline">
                        <input type="text" class="form-control" id="TagName" name="TagName" autofocus value="{{old('TagName')}}" style="height: 3rem;">
                        <label for="TagName" class="">Tag Name</label>
                        @if($errors->AddTag->has('TagName'))
                          <span class="text-danger">{{$errors->AddTag->first('TagName')}}</span>
                        @endif
                      </div>
                      <div class="md-form md-outline">
                        <textarea type="text" class="form-control" id="TagDescription" name="TagDescription" rows="6">{{old('TagDescription')}}</textarea>
                        <label for="TagDescription" class="">Tag Description</label>
                        @if($errors->AddTag->has('TagDescription'))
                          <span class="text-danger">{{$errors->AddTag->first('TagDescription')}}</span>
                        @endif
                      </div>
                      <div class="md-form md-outline">
                        <input type="color" class="form-control color-input" id="TagColor" name="TagColor" autofocus value="{{old('TagColor')}}">
                        <label for="TagColor" class="">Select Tag Color</label>
                        @if($errors->AddTag->has('TagColor'))
                          <span class="text-danger">{{$errors->AddTag->first('TagColor')}}</span>
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
        <div class="modal fade" id="edit_tag_modal" tabindex="-1" role="dialog" aria-labelledby="ModalLabel" aria-hidden="true">
          <div class="modal-dialog" role="document">
            <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title" id="ModalLabel">Edit Tag</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
              </div>
              <div class="modal-body">
                <form method="post" action="{{route('UpdateTag')}}">
                  @csrf
                  <input type="hidden" id="EditTagId" name="EditTagId" value="{{old('EditTagId')}}">
                  <div class="row">
                    <!-- Grid column -->
                    <div class="col-md-10 offset-1">
                      <div class="md-form md-outline">
                        <input type="text" class="form-control" id="EditTagName" name="EditTagName" autofocus value="{{old('EditTagName')}}" style="height: 3rem;">
                        <label for="EditTagName" class="">Tag Name</label>
                        @if($errors->EditTag->has('EditTagName'))
                          <span class="text-danger">{{$errors->EditTag->first('EditTagName')}}</span>
                        @endif
                      </div>
                      <div class="md-form md-outline">
                        <textarea type="text" class="form-control" id="EditTagDescription" name="EditTagDescription" rows="6" autofocus >{{old('EditTagDescription')}}</textarea>
                        <label id="forEditTagDesc" for="EditTagDescription" class="">Tag Description</label>
                        @if($errors->EditTag->has('EditTagDescription'))
                          <span class="text-danger">{{$errors->EditTag->first('EditTagDescription')}}</span>
                        @endif
                      </div>
                      <div class="md-form md-outline">
                        <input type="color" class="form-control color-input" id="EditTagColor" name="EditTagColor" autofocus value="{{old('EditTagColor')}}">
                        <label for="EditTagColor" class="">Select Tag Color</label>
                        @if($errors->EditTag->has('EditTagColor'))
                          <span class="text-danger">{{$errors->EditTag->first('EditTagColor')}}</span>
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
