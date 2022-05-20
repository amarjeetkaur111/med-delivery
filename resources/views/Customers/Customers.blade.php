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
        </div>        
          <div class="card">
            <div class="card-body">
      				<div class="row">
      					<div class="col-md-6"><h4 class="mt-3">Customers</h4>
                </div>
                <div class="col-md-6">
                  <div class="float-right">
                    <a href="/customers/add_customer"><button type="button" class="btn btn-outline-primary waves-effect"><i class="icon-item fas fa-user-alt" role="button" data-prefix="fas" data-id="user-alt" data-unicode="f406" data-mdb-original-title="" title="" aria-describedby="tooltip529027"></i>  Add Customer</button></a> 
                  </div>
                </div>
      				</div>
		        	<hr>
              <table id="customer_table" class="table table-striped" cellspacing="0" width="100%">
                <thead>
                  <tr>
                    <th>Sr.No</th>
                    <th>Customer Name</th>
                    <th>Phone Number</th>
                    <th>Primary Address</th>
                    <!-- <th>Email</th> -->
                    <th>Edit</th>
                    <th>Status</th>
                  </tr>
                </thead>
                <tbody>
                  @foreach($data as $data)
                  <tr>
                    <td>{{$data->CustomerId}}</td>
                    <td>{{$data->FirstName}} {{$data->LastName}}</td>
                    <td>@foreach($data->PhoneNumbers as $phone){{$phone->PhoneNumber}}<br>@endforeach</td>                
                    <td>{{$data->AddressLine}} {{$data->City}} {{$data->Province}}</td>
                    <!-- <td></td> -->
          					<td><a href="/customers/edit_customer/{{encval($data->CustomerId)}}" ><i class="icon-item fas fa-user-edit" role="button" data-prefix="fas" data-id="user-edit" data-unicode="f4ff" data-mdb-original-title="" title=""></i></a></td>

          					<td><a href="javascript:void(0);" data-name="{{$data->FirstName}} {{$data->LastName}}" data-id="{{encval($data->CustomerId)}}" id="deleteCustomer">
                      <i class="icon-item fas @if($data->Status == 1) {{"fa-toggle-on activate"}} @else {{"fa-toggle-off deactivate"}} @endif role="button" data-prefix="fas" data-id="" title="Activate/Deactivate" style="font-size: 25px;"></i>
                    </a>
                    </td>
                  </tr>   
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
<script src="{{asset('js/Customer/Customer.js')}}"></script>
