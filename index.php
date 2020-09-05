<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Başlıksız Belge</title>
<script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script>
<script>
$(document).ready(function() {
	$('#linkler').load("hareket.php?islem=dosyalar");
	$('.islembasliyor').hide();
	$('#txtgoster').hide();
	$('#wordgoster').hide();
	$('#btn').click(function() {
		$.ajax({
			type:"POST",
			url:'hareket.php?islem=formgelsin',
			data:$('#getir').serialize(),
			success: function(donen_bilgi) {
				$('#getir').trigger("reset");				
				$('#elemangoster').html(donen_bilgi).slideDown();				
			}
		})
	})
	$('input').on('change',function() {
		var deger=$('input[type="radio"][name="sec"]:checked').val();
		if (deger=="txt") {
			$('#bilgi').hide();
			$('#wordgoster').hide();
			$('#ana').fadeIn();	
			$('#txtgoster').fadeIn();
		}
		else if (deger=="word") {
			$('#ana').hide();
			$('#txtgoster').hide();	
			$('#wordgoster').fadeIn();
		}
	}) // radio değerler
	$('#txtekle').click(function() {
		$.ajax({
			type:"POST",
			url:'hareket.php?islem=txtekle',
			data:$('#txtyap').serialize(),
			success: function(donen_bilgi) {
				$('#linkler').load("hareket.php?islem=dosyalar");
				$('#dosya').trigger("reset");
				$('#txtyap').trigger("reset");					
				$('#txtyap').fadeOut(800,function() {
					
					$('#txtsonuc').html(donen_bilgi).fadeIn().delay(2000);
				});
			}
		})
	})  // txt ekleniyor
	$('#wordekle').click(function() {
		$.ajax({
			type:"POST",
			url:'hareket.php?islem=wordekle',
			data:$('#wordyap').serialize(),
			success: function(donen_bilgi) {
				$('#linkler').load("hareket.php?islem=dosyalar");
				$('#dosya').trigger("reset");
				$('#wordyap').trigger("reset");					
				$('#wordyap').fadeOut(800,function() {
					
					$('#wordsonuc').html(donen_bilgi).fadeIn().delay(2000);
				
				});
			}
		})
	}) // word ekleniyor
	$('#klasorekle').click(function() {
		$.ajax({
			type:"POST",
			url:'hareket.php?islem=klasorolustur',
			data:$('#klasorolustur').serialize(),
			success: function(donen_bilgi) {	
				$('#linkler').load("hareket.php?islem=dosyalar");
				$('#klasorolustur').trigger("reset");					
				$('#klasorsonuc').html(donen_bilgi).fadeIn(1000);
				$('#klasorsonuc').fadeOut(2000).delay(7000);
			}
		})
	}) // klasör ekle
});
</script>
<style> html,body {height: 100%;} </style>
</head>
<body>
<div class="container-fluid h-100">
	<div class="row justify-content-center h-100">
	    <div class="col-md-3 text-center border p-3 bg-light">
	        <div class="row">
	            <div class="col-md-12" style="min-height:120px;">
	                <form id="getir">
		                <label>Kaç adet gelsin</label><br />
		                <input type="text" name="sayi" class="form-control" />
		                <input type="button" id="btn" value="OLUŞTUR" class="btn btn-danger mt-2" /> 
		            </form>
	            </div>
	            <!-- ******************************* -->  
	            <div class="col-md-12 border-top mt-1 text-center" id="elemangoster" style="min-height:140px;">
	            	<div class="alert alert-info mt-5">Lütfen yüklenecek dosya adetini seçiniz</div>  			
	        	</div> 
		        <!-- ******************************* --> 
		        <div class="col-md-12 border-top mt-1 text-center text-danger" id="sonucgoster" style="min-height:140px;"> 
		        	<div class="alert alert-warning mt-5">Yükleme sonucunu burada göreceksiniz</div> 
		    	</div> 
			</div>
			<!-- ******************************* --> 
		</div>
		<div class="col-md-9 justify-content-center h-100 ">
		    <div class="row">
		    <!-- BURADA DOSYA OLUŞTURMA -->
		    <!-- FORMAT SEÇİMİ -->   
		    	<div class="col-md-6 bg-light border border-right justify-content-center h-100">
		            <div class="row">
		                <div class="col-md-12 text-center"><h4>HANGİ DOSYADAN OLUŞTURACAĞIZ</h4></div> 
		                <div class="col-md-12 text-center">
	                        <form id="dosya">                
	                			<label> Txt <input type="radio" name="sec" value="txt" /></label>
	                			<label> Word <input type="radio" name="sec" value="word" /></label>          
				            </form>
		                </div>
		                <!-- FORMAT SEÇİMİ -->   
		                <!-- TXT SEÇİMİ -->   
	                    <div class="col-md-12 text-center border border-bottom" id="ana" style="min-height:420px;">
	                     	<div class="alert alert-danger mt-5" id="bilgi">DOSYA FORMATINI SEÇİNİZ</div>                 
							<!-- TXT FORM --> 
	                        <div id="txtgoster">
		                        <form id="txtyap">
	           						<textarea class="form-control" cols="27" rows="10" name="metin"></textarea>
					                <input type="text" name="dosyaad"  class="form-control mt-2" placeholder="Dosya adı ne olsun ?" />
	          						<select name="klasoryolu" class="form-control mt-2">
	          						<?php
										$dosyalar =scandir(".");
										foreach($dosyalar as $dosya):
											if ($dosya=='.' || $dosya=='..' ||$dosya=='hareket.php' || $dosya=='index.php' || $dosya=='load.gif') 
										 	continue;
										 	echo '<option value="'.$dosya.'">'.$dosya.'</option>';
										endforeach;
									?>
							        </select>
	                				<input type="button" id="txtekle" value="Txt Ekle" class="btn btn-info mt-2" />
	                			</form>                
	                			<div id="txtsonuc"></div>	                         
	                        </div>
	                    </div>
	                    <!-- TXT SEÇİMİ --> 
	                    <!-- WORD SEÇİMİ --> 
	                    <div class="col-md-12 text-center" id="wordgoster" style="min-height:420px;">
	                        <form id="wordyap">
	           					<textarea class="form-control" cols="27" rows="10" name="metin"></textarea>
	                			<input type="text" name="dosyaad"  class="form-control mt-2" placeholder="Dosya adı ne olsun ?" />
						        <select name="klasoryolu" class="form-control mt-2">
	          					<?php
			  						$dosyalar =scandir(".");
			  						foreach($dosyalar as $dosya):
										if ($dosya=='.' || $dosya=='..' ||$dosya=='hareket.php' || $dosya=='index.php' || $dosya=='load.gif') 
										continue;
										echo '<option value="'.$dosya.'">'.$dosya.'</option>';
									endforeach;
								?>
					          	</select>
					            <input type="button" id="wordekle" value="Word Ekle" class="btn btn-info mt-2" />
	                		</form>
	                		<div id="wordsonuc"></div>
	                        <!-- WORD FORM --> 
	                    </div>
	                    <!-- WORD SEÇİMİ --> 
	                	<!-- KLASÖR OLUŞTURMA -->   
	                	<div class="col-md-12 text-center" id="klasorolusturdiv" style="min-height:100px;">                     
	                       	<form id="klasorolustur">
						        <input type="text" name="klasorad"  class="form-control mt-2" placeholder="Klasör Adı" />
	            				<input type="button" id="klasorekle" value="KLASÖR OLUŞTUR" class="btn btn-info mt-2" />
	                		</form>
	          	 			<div id="klasorsonuc"></div>
	                    </div>
	                 	<!-- KLASÖR OLUŞTURMA -->  
		                <!-- BURADA DOSYA OLUŞTURMA -->   
		          	</div>
				</div>
				<!-- BURADA DOSYALARI GÖRME -->   
		        <div class="col-md-6 bg-light border border-right justify-content-center h-100">
	                <div class="row">
	                	<div class="col-md-12" id="linkler"></div>
		            </div>
		        </div>
				<!-- BURADA DOSYALARI GÖRME -->
			</div>
		</div>
	</div>
</div>
</body>
</html>