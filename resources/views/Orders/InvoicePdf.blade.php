<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <title>Medication Delivery Invoice</title>
    <style>
        @font-face {
  font-family: SourceSansPro;
  src: url(SourceSansPro-Regular.ttf);
}

.clearfix:after {
  content: "";
  display: table;
  clear: both;
}

a {
  color: #0087C3;
  text-decoration: none;
}

body {
  position: relative;
  width: 100%;
  height: 29.7cm;
  margin: 0 auto;
  color: #555555;
  background: #FFFFFF;
  font-family: Arial, sans-serif;
  font-size: 14px;
  font-family: SourceSansPro;
}

header {
  background: #4285f4;
  color: white;
  margin-left: -2rem;
  width: 109%;
  margin-top: -2rem;
  /*padding: 2px 0;*/
  margin-bottom: 20px;
  border-bottom: 1px solid #AAAAAA;
}

#header {
  float: left;
  margin-top: 8px;
}

#invoice-detail {
  float: right;
  text-align: right;
}


#details {
  margin-bottom: 50px;
}

#client {
  padding-left: 6px;
  border-left: 6px solid #0087C3;
  float: left;
}

#client .to {
  color: #777777;
}

h2.name {
  font-size: 1.4em;
  font-weight: normal;
  margin: 0;
}

#physician {
  float: right;
  text-align: right;
}

#physician h1 {
  color: #0087C3;
  font-size: 2.4em;
  line-height: 1em;
  font-weight: normal;
  margin: 0  0 10px 0;
}

#physician .date {
  font-size: 1.1em;
  color: #777777;
}

table {
  width: 100%;
  border-collapse: collapse;
  border-spacing: 0;
  margin-bottom: 0px;
}

table th,
table td {
  padding: 5px;
  background: #fff;
  text-align: center;
  border-bottom: 1px solid #FFFFFF;
}

table th {
  white-space: nowrap;
  font-weight: normal;
  background: #c8daed;

}

table td {
  text-align: right;
}

table td h3{
  color: #c8daed;
  font-size: 1.2em;
  font-weight: normal;
  margin: 0 0 0.2em 0;
}

table .no {
  background: #c8daed;

}

table .desc {
  text-align: left;
}

table .unit {
  /*background: #DDDDDD;*/
  text-align: center;

}

table .qty {
  text-align: center;

}


table .total {
  color: #000;
  text-align: center;

}

table td.unit,
table td.qty,
table td.total,
table td.desc {
  font-size: 1rem;
  padding-bottom: -10px !important;
  margin-bottom: -10px !important;
}
table tbody tr:last-child td {
  border: none;
}

table tfoot td {
  padding: 10px 20px;
  background: #FFFFFF;
  border-bottom: none;
  font-size: 1.2em;
  white-space: nowrap;
  border-top: 1px solid #AAAAAA;
}

table tfoot tr:first-child td {
  border-top: none;
}

table tfoot tr:last-child td {
  color: #024387;
  font-size: 1.4em;
  border-top: 1px solid #c8daed;

}

table tfoot tr td:first-child {
  border: none;
}

#thanks{
  font-size: 2em;
  margin-bottom: 50px;
}

#notices{
  padding-left: 6px;
  border-left: 6px solid #0087C3;
}

#notices .notice {
  font-size: 1.2em;
}

footer {
  color: #777777;
  width: 100%;
  height: 30px;
  position: absolute;
  bottom: 0;
  border-top: 1px solid #AAAAAA;
  padding: 8px 0;
  text-align: center;
}


    </style>
  </head>
  <body>
    <?php $visit[0]['customer']['PhoneNumbers']=json_decode($visit[0]['customer']['PhoneNumbers']);
      $visitid = $visit[0]['CustomerVisitID'];
     ?>
    <header class="clearfix">
      <div id="header">
        @if(file_exists(public_path('img/Rx360Logo.png')))      
          <img src="{{public_path('img/Rx360Logo.png')}}" id="logoimg" style="margin-left: 40px;margin-top: 20px;width: 7rem;">
        @endif
      </div>
      <div id="invoice-detail" style="padding-right: 10px;">
         <h2 style="margin-bottom: 10px;">INVOICE: {{date('Ymd').$visitid}}</h2>
          <div class="date">Date of Invoice: <?php echo date('d-m-Y'); ?></div>
          <div class="date" style="margin-bottom:10px">Due Date: <?php echo date('d-m-Y',strtotime("+1 month")); ?></div>
      </div>
      </div>
    </header>
    <main>
      <div id="details" class="clearfix">
        <div id="client">
          <div class="to">Patient Information</div>
          <h2 class="name">{{$visit[0]['customer']['FirstName']}} {{$visit[0]['customer']['LastName']}}</h2>
          <div class="date">{{$visit[0]['customer']['PhoneNumbers'][0]->PhoneNumber}}</div>
          <div class="address">{{$visit[0]['customer']['customers_address'][0]['AddressLine']}}</div>
          <div class="address">{{$visit[0]['customer']['customers_address'][0]['City']}} {{$visit[0]['customer']['customers_address'][0]['Province']}}</div>
        </div>
        <div id="physician">
          <div class="to">Pharmacy Information</div>
          <h2 class="name">{{$visit[0]['customer']['pharmacy_customer']['pharmacy']['PharmacyName'] ?? 'Not Assigned'}}</h2>
          <div class="date">{{$visit[0]['customer']['pharmacy_customer']['pharmacy']['PharmacyPhone'] ?? 'Not Assigned'}}</div>
        </div>

      </div>
      <div id="details" class="clearfix" style="margin-top:-70px; margin-bottom: -10px;">
        <div id="physician">
          <img src="data:image/png;base64,{{DNS2D::getBarcodePNG(ucwords($visitid), 'QRCODE')}}" class="img-fluid" alt="" name="Barcode" style="height:4rem;">
        </div>
      </div>
      <h2 class="name">Medication Equipment/Supply</h2>
      <table border="0" cellspacing="0" cellpadding="0" style="margin-top: 10px;">
        <thead>
          <tr>
            <th class="no">#</th>
            <th class="desc">DESCRIgit PTION</th>
            <th class="unit">UNIT PRICE</th>
            <th class="qty">QUANTITY</th>
            <th class="total">TOTAL</th>
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
            <td class="desc"><h4>{{$visit['goods']['GoodsName']}}</h4></td>
            <td class="unit">${{$visit['GoodsAmt']}}</td>
            <td class="qty">{{$visit['GoodsQty']}}</td>
            <td class="total">${{$visit['GoodsAmt']*$visit['GoodsQty']}}</td>
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
      <div id="thanks">Thank you!</div>
      <div id="notices">
        <div>NOTICE:</div>
        <div class="notice">xxxx</div>
      </div>
    </main>
    <footer>
      Invoice was created on a computer and is valid without the signature and seal.
    </footer>
  </body>
</html>
<script>
  $("#logoimg").error(function () { 
    $("#header").css(display:"none !important"); 
});
</script>