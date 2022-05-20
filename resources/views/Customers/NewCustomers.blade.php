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
<script src="{{asset('js/Customer/NewCustomer.js')}}"></script>
