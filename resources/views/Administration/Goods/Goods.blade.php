@include('Includes.Header')
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
                <div class="col-md-6"><h4 class="mt-3">Goods List</h4>
                </div>
                 <div class="col-md-6">
                  <div class="float-right">
                    <a href="/administration/goods/add_goods"><button type="button" class="btn btn-outline-primary waves-effect">+ Add Goods</button></a>  
                    <a href="/administration/goods/goods_type"><button type="button" class="btn btn-outline-primary waves-effect">Goods Type</button></a>  
                  </div>
                </div>
              </div>
              <hr>              
              <table id="medication_table" class="table table-striped" cellspacing="0" width="100%">
                <thead>
                  <tr>
                    <th>Goods Type</th>
                    <th>Goods Name</th>                    
                    <th>Cost</th>
                    <th>Quantity</th>
                    <th>Edit</th>
                    <th>Delete</th>
                  </tr>
                </thead>
                <tbody>  
                @foreach($data as $data) 
                <?php $typename =  $data['GoodsTypeName']; ?>
                  @foreach($data['Goods'] as $goods)                
                  <tr>
                    <td><?php echo $typename;?></td>
                    <td>{{$goods['GoodsName']}}</td>
                    <td>{{$goods['Cost']}}</td>
                    <td>{{$goods['Quantity']}}</td>
                    <td><a href="/administration/goods/edit_goods/{{encval($goods['GoodsId'])}}" ><i class="icon-item fas fa-user-edit" role="button" data-prefix="fas" data-id="user-edit" data-unicode="f4ff" data-mdb-original-title="" title=""></i></a></td>

                    <td><a href="javascript:void(0);" data-name="{{$goods['GoodsName']}}" data-id="{{encval($goods['GoodsId'])}}" id="deleteGoods"><i class="icon-item fas fa-trash-alt" role="button" data-prefix="fas" data-id="trash-alt" data-unicode="f2ed" data-mdb-original-title="" title=""></i></a></td>
                   </tr>    
                  @endforeach
                  @endforeach  
                </tbody>
                
              </table>
            </div>
          </div>

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
<script>
   
</script>
