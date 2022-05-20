
@include('Includes.Header') 
<meta name="csrf-token" content="{{ csrf_token() }}">
<?php 
  if($map_status){  
?>
    <script src="https://cdn.socket.io/socket.io-3.0.1.min.js"></script>
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCEggTYQKQ_7Qg0CJQvuPwb0lmOXI05wjE"></script>
 <style>
 #patientmap {  height: 800px;  width: 100%;  top: 4rem; margin: 0px;  padding: 0px  }
.sidebar{
  position:fixed;
  top:50%;
  right:-100%;
  transform:translateY(-50%);
  width:350px;
  height:100vh;
  min-height:100%;
  padding:40px;
  background:#fff;
  box-shadow: 0 20px 50px rgba(0,0,0,.5);
  box-sizing:border-box;
  transition:0.5s;
  z-index: 9999;
  overflow-y:auto;
}
.sidebar.active{
  right:0;
}
.sidebar input,
.sidebar textarea{
  width:100%;
  height:36px;
  padding:5px;
  margin-bottom:10px;
  box-sizing:border-box;
  border:1px solid rgba(0,0,0,.5);
  outline:none;
}
.sidebar h2{
  margin:0 0 20px;
  padding:0;
}
.toggle{
  position: absolute;
height: 28px;
width: 28px;
text-align: center;
cursor: pointer;
background: #ca4040;
top: 5px;
right: 5px;
line-height: 28px;
  
}

#contact_close i{
  color: #fff;
  margin: 1px 0px 0 0;
  font-size: 15px;
}

.placeholder{color: grey;}
select option:first-child{color: grey; display: none;}
select option{color: #555;}

#chartimg{ margin:5px 3px 0 0;width:180px;background-color:#f2f2f2; padding:12px 15px; font-size: 13px; }
#chartimg h5{font-weight: 400; font-size: 16px;}
#chartimg i{padding-right: 10px; font-size: 15px; }
#chartimg p{margin-bottom: 0.5rem;}
</style>
<div class="container-fluid section card" style="margin-top:100px;"> 
  <?php   
//   $maps = array(); 
//   foreach($Patients_geoinfo as $pmaps){ 

//   } 
  // print_r($maps);exit;  
  $did = isset($InputData['did']) ?  $InputData['did'] : null;
  $pid = isset($InputData['pid']) ?  $InputData['pid'] : null;
  ?>
  <script>     
    const locations_og = <?php echo json_encode($Patients_geoinfo); ?>; 
    const driver_ids = <?php echo json_encode($driver_ids); ?>; 
    console.log('driver_ids',driver_ids);
    console.log('locations_og',locations_og);
    // alert(locations[0].Geometry.Latitude);
  </script> 
  
  <div class="">
    <div id="chartimg" style="">
      <div class="statuschart">
        <h5 style="">Customer Visit Status</h5>
        <p><i class="fa fa-map-marker" aria-hidden="true" style="color:#ff776b;"></i>New Order<br></p>
        <p><i class="fa fa-map-marker" aria-hidden="true" style="color:#00ff00;"></i>Delivered<br></p>
        <p><i class="fa fa-map-marker" aria-hidden="true" style="color:#0099ff;"></i>Skip<br></p>
        <p><i class="fa fa-map-marker" aria-hidden="true" style="color:#cccccc;"></i>Cancel<br></p>
        <p><i class="fa fa-map-marker" aria-hidden="true" style="color:#ff33cc;"></i>Postpone<br></p>
        <p><i class="fa fa-map-marker" aria-hidden="true" style="color:#f9f900;"></i>Return<br></p>
        <p><i class="fa fa-map-marker" aria-hidden="true" style="color:#fff;"></i>Undelivered<br></p>
        <!-- <h3>#Nurse Name</h3>
        <p>Start Point: # </p>
        <p>End Point: #</p>
        <p>Distance: #</p>
        <p>No of Stops: #</p>
        <p>Travel Duration: #</p>
        <p>Time Spend With Customer: #</p>
        <p>Patient Name: #</p>
        <p>Task Done: #</p> -->


      </div>
      <!-- <img src="{{asset('img/status.png')}}" style="margin:0 3px 0 0;width:200px">   -->

    </div>
    <div style="margin-top: 10px;padding:2px;backgroud:#fff;" id="route-input">
      <!-- <span style="font-size:13px;"> Switch Route</span> -->    
      <form method="post" action="{{route('MapFilter')}}">
      @csrf 
      <select class="form-control" name="Pharmacy" onchange="this.form.submit()" style="width:200px; float: left;">
          <option>Switch Pharmacy</option>
          <option>Select Pharmacy</option>
          @foreach($pharmacy as $pharmacy)
            <option value="{{$pharmacy['PharmacyId']}}" @if($pharmacy['PharmacyId'] == $pid) {{'selected'}} @endif>{{$pharmacy['PharmacyName']}}</option>
          @endforeach
        </select>

        <select class="form-control" name="Driver" onchange="this.form.submit()" style="width:200px; float:right;">
          <option>Switch Driver</option>
          <option>Select Driver</option>
          @foreach($driver as $driver)
            <option value="{{$driver['Id']}}" @if($driver['Id'] == $did) {{'selected'}} @endif>{{ucwords($driver['FirstName'].' '.$driver['LastName'])}}</option>
          @endforeach
        </select>
      </form>
    </div>
  </div>

  <div id="patientmap"></div>
  
  <div class="sidebar">
  <div class="toggle contact_close" ><i  class="fas fa-times "></i></div>
    
    <h2>Customer Info</h2>
    <div class="scroll" id="patientinfoappend">
    </div>
    <hr>
    <h2>Goods Details</h2>
    <div class="scroll" >
      <table class="table" id="patientctstatusappend">

      </table>  
    </div>
    <hr>
    <h2>Driver's Info</h2>
    <div class="scroll" id="driverinfoappend">
    </div>
  </div>

</div>
  <?php }else{  ?>
  
  <div style="margin:20% 0 0 0;text-align: center;color:red;"><h1>No Order For Today</h1><p>
  </div> 
  <?php  } ?> 
  <script src="{{asset('js/Map/MapNewScript.js')}}"></script>
  @include('Includes.Footer')
