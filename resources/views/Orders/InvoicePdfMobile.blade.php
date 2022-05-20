  <link rel="stylesheet" href="{{ asset('css/bootstrap.min.css') }}">
  <script src="{{asset('js/jquery-3.5.1.min.js')}}"></script>
  <link rel="stylesheet" type="text/css" href="{{ asset('css/style.css') }}">

  <?php $visit[0]['customer']['PhoneNumbers']=json_decode($visit[0]['customer']['PhoneNumbers']);
    $visitid = $visit[0]['CustomerVisitID'];
   ?>
 <div class="mobile-invoice offset-xl-2 col-xl-8 col-lg-12 col-md-12 col-sm-12 col-12 padding mt-4">
     <div class="card">
         <div class="card-header">
         @if(file_exists(asset('img/Rx360Logo.png'))) 
             <img src="{{asset('img/Rx360Logo.png')}}">
          @endif
             <div class="float-right header-detail text-right">
                  <h2>INVOICE: {{date('Ymd').$visitid}}</h2>
                  <div class="invoice-date mb-n2"><h5>Date of Invoice: <?php echo date('d-m-Y'); ?></h5></div>
                  <div class="due-date"><h5>Due Date: <?php echo date('d-m-Y',strtotime("+1 month")); ?></h5></div>
             </div>
         </div>
         <div class="card-body">
             <div class="row mb-2 client-details">
                 <div class="col-sm-6">
                    <div class="client">
                      <div class="to"><h5>Patient Information</h5></div>
                      <h4 class="name mb-0">{{$visit[0]['customer']['FirstName']}} {{$visit[0]['customer']['LastName']}}</h4>
                      <div class="date mb-n2"><h5>{{$visit[0]['customer']['PhoneNumbers'][0]->PhoneNumber}}</h5></div>
                      <div class="address mb-n2"><h5>{{$visit[0]['customer']['customers_address'][0]['AddressLine']}}</h5></div>
                      <div class="address"><h5>{{$visit[0]['customer']['customers_address'][0]['City']}} {{$visit[0]['customer']['customers_address'][0]['Province']}}</h5></div>
                    </div>
                 </div>
                 <div class="col-sm-6 text-right">
                    <div class="to"><h5>Pharmacy Information</h5></div>
                    <h4 class="name mb-0">{{$visit[0]['customer']['pharmacy_customer']['pharmacy']['PharmacyName'] ?? 'Not Assigned'}}</h4>
                    <div class="date"><h5>{{$visit[0]['customer']['pharmacy_customer']['pharmacy']['PharmacyPhone'] ?? 'Not Assigned'}}</h5></div>                     
                 </div>
             </div>
             <div class="row client-details">
                <div class="col-sm-6 mb-4">
                  <img src="data:image/png;base64,{{DNS2D::getBarcodePNG(ucwords($visitid), 'QRCODE')}}" class="img-fluid" alt="" name="Barcode" style="height:4rem;width: 4rem;">
                </div>
              </div>
             <h4>Medication Equipment/Supply</h4>
             <div class="table-responsive-sm">
                 <table class="table table-striped" width="100%">
                     <thead>
                         <tr>
                             <th class="center no">#</th>
                             <th>DESCRIPTION</th>
                             <th class="right">UNIT PRICE</th>
                             <th class="center">QUANTITY</th>
                             <th class="right">TOTAL</th>
                         </tr>
                     </thead>
                     <tbody>
                         <?php
                          $total = 0;
                          $no = 1
                        ?>
                        @foreach($visit[0]['status'] as $visit)
                        <tr>
                          <td class="no">{{$no}}</td>
                          <td class="desc text-center"><h4>{{$visit['goods']['GoodsName']}}</h4></td>
                          <td class="unit"><h4>${{$visit['GoodsAmt']}}</h4></td>
                          <td class="qty">{{$visit['GoodsQty']}}</td>
                          <td class="total"><h4>${{$visit['GoodsAmt']*$visit['GoodsQty']}}</h4></td>
                        </tr>
                        @php $total = $total + ($visit['GoodsAmt']*$visit['GoodsQty']); $no++; @endphp
                        @endforeach
                      </tbody>
                      <tfoot>
                        <tr>
                          <td colspan="2"></td>
                          <td colspan="2">SUBTOTAL</td>
                          <td>${{$total}}</td>
                        </tr>
                        <tr>
                          <td colspan="2"></td>
                          <td colspan="2">TAX 25%</td>
                          <td>${{$total*25/100}}</td>
                        </tr>
                        <tr>
                          <td colspan="2"></td>
                          <td colspan="2">GRAND TOTAL</td>
                          <td>${{($total*25/100)+$total}}</td>
                        </tr>
                      </tfoot>
                 </table>
             </div>
             <div class="row">
                <div class="col-sm-12">
                  <h4>Thank you!</h4>
                  <div id="notices">
                    <div>NOTICE:</div>
                    <div class="notice">xxxx</div>
                  </div>                
                </div>               
             </div>
         </div>
         <div class="card-footer bg-white text-center">
             <p class="mb-0">Invoice was created on a computer and is valid without the signature and seal.</p>
         </div>
     </div>
 </div>