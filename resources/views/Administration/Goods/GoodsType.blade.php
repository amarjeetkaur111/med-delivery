@include('Includes.Header')
@if($errors->AddMedType->any())
  <script>
     $(function(){
         $('#add_goods_type').modal('show');
     });
  </script>
@enderror
@if($errors->EditMedType->any())
  <script>
     $(function(){
         $('#edit_goodstype_modal').modal('show');
     });
  </script>
@enderror
  <!-- Main Navigation  -->
  <!-- Main layout  -->
  <main>
    <div class="customer-list container-fluid mb-5 cont-margin">
      <!-- Section: Basic examples -->
      <section>
        <!-- Gird column -->
        <div class="col-md-12">
          <h5 class="my-4 dark-grey-text font-weight-bold"></h5>
          <div class="row">
          <div class="col-md-6 heading text-left">            
          </div> 
          <div class="col-md-6 heading text-right">           
          </div>         
        </div>
          <div class="card">
            <div class="card-body">
              <div class="row">
                <div class="col-md-6"><h4 class="mt-3">Goods-Type List</h4>
                </div>
                <div class="col-md-6">
                  <div class="float-right">
                    <a href="/administration/goods"><button type="button" class="btn btn-outline-primary waves-effect">Back</button></a>        
                    <a><button type="button" class="btn btn-outline-primary waves-effect" data-toggle="modal" data-target="#add_goods_type" >Add Goods Type</button></a> 
                  </div>
                </div>
              </div>
              <hr>              
              <table id="medication_table" class="table table-striped" cellspacing="0" width="100%">
                <thead>
                  <tr>
                    <th>Goods Type ID</th>
                    <th>Goods Type Name</th>
                    <th>Edit</th>
                    <th>Delete</th>
                  </tr>
                </thead>
                <tbody>  
                @foreach($data as $data) 
                  <tr>
                    <td>{{$data['GoodsTypeId']}}</td>
                    <td>{{$data['GoodsTypeName']}}</td>
                    <td><a href="" id="edit_goodstype" data-toggle="modal" data-target='#edit_goodstype_modal' data-id="{{encval($data['GoodsTypeId'])}}"><i class="icon-item fas fa-user-edit" role="button" data-prefix="fas" data-id="user-edit" data-unicode="f4ff" data-mdb-original-title="" title=""></i></a></td>

                    <td><a href="javascript:void(0);" data-name="{{$data['GoodsTypeName']}}" data-id="{{encval($data['GoodsTypeId'])}}" id="deleteGoodstype"><i class="icon-item fas fa-trash-alt" role="button" data-prefix="fas" data-id="trash-alt" data-unicode="f2ed" data-mdb-original-title="" title=""></i></a></td>
                   </tr>    
                  @endforeach  
                </tbody>                
              </table>
            </div>
          </div>
        </div>
        <!-- Add Modal -->
        <div class="modal fade" id="add_goods_type" tabindex="-1" role="dialog" aria-labelledby="ModalLabel" aria-hidden="true">
          <div class="modal-dialog" role="document">
            <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title" id="ModalLabel">Add New Goods Type</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
              </div>
              <div class="modal-body">
                <form method="post" action="{{route('AddGoodsType')}}">
                  @csrf
                  <div class="row">
                    <!-- Grid column -->
                    <div class="col-md-10 offset-1">
                      <div class="md-form md-outline">
                        <input type="text" class="form-control" id="GoodsTypeName" name="GoodsTypeName" autofocus value="{{old('GoodsTypeName')}}" style="height: 3rem;">
                        <label for="GoodsTypeName" class="">Goods Type Name</label>
                        @if($errors->AddMedType->has('GoodsTypeName'))
                          <span class="text-danger">{{$errors->AddMedType->first('GoodsTypeName')}}</span>
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
        <div class="modal fade" id="edit_goodstype_modal" tabindex="-1" role="dialog" aria-labelledby="ModalLabel" aria-hidden="true">
          <div class="modal-dialog" role="document">
            <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title" id="ModalLabel">Edit Goods Type</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
              </div>
              <div class="modal-body">
                <form method="post" action="{{route('UpdateGoodsType')}}">
                  @csrf
                  <input type="hidden" id="EditGoodsTypeId" name="EditGoodsTypeId" value="{{old('EditGoodsTypeId')}}">
                  <div class="row">
                    <!-- Grid column -->
                    <div class="col-md-10 offset-1">
                      <div class="md-form md-outline">
                        <input type="text" class="form-control" id="EditGoodsTypeName" name="EditGoodsTypeName" autofocus value="{{old('EditGoodsTypeName')}}" style="height: 3rem;">
                        <label for="EditGoodsTypeName" class="">Goods Type Name</label>
                        @if($errors->EditMedType->has('EditGoodsTypeName'))
                          <span class="text-danger">{{$errors->EditMedType->first('EditGoodsTypeName')}}</span>
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
