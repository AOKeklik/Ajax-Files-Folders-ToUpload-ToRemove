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
	$('#yuklebakalim').click(function(e) {		
		e.preventDefault();
		var formData = new FormData($('#uploadform')[0]);	
		$.ajax({
			beforeSend: function () {
				$('.islembasliyor').show();	
			},
			type:"POST",
			url:'hareket.php?islem=elemangetir',
			enctype: 'multipart/form-data',
			data:formData,
			processData:false,
			contentType:false,
			}).done(function(donen_bilgi) {
				$('#sonucgoster').html(donen_bilgi).slideDown();	
				$('#uploadform').trigger("reset");	
				$('#elemangoster').html('<div class="alert alert-warning mt-5">Dosyalar gönderildi</div>');								
			}).complete(function(){
				$('.islembasliyor').hide();	
			 	$('#uploadform').trigger("reset");		
			});
		})
		$('#tekrartxt').click(function() {
			window.location.reload();
		});
		$('#wordtxt').click(function() {
			window.location.reload();
		});

	$('#linkler a').click(function() {
		var sectionId=$(this).attr('sectionId');
		var sectionId2=$(this).attr('sectionId2');
		$.post("hareket.php?islem=silmeharekati",{"dosyaad":sectionId,"klasorad":sectionId2},function(post_veri) {
			$('#dosyasonuc'+sectionId2).html(post_veri).fadeIn(1000,function(){
				$('#dosyasonuc'+sectionId2).fadeOut(2200,function(){
					$('#linkler').load("hareket.php?islem=dosyalar");
				});
			});
		});
	})
});
</script>
</head>
<body>
<?php
	@$islem=$_GET["islem"];

	switch ($islem) :
		case "formgelsin":
			@$sayi= $_POST["sayi"];
			$sayi++;
			echo '<form id="uploadform">';
			for ($i=1; $i<$sayi; $i++) :
				echo '<input type="file" name="dosya'.$i.'" ><br><br>';
			endfor; 
?>
<input type="hidden" name="say" value="<?php echo $sayi; ?>" />
<input type="button" id="yuklebakalim" class="btn btn-success" value="YÜKLE" />
</form>
<?php
	break; // inputlar geliyor
	case "elemangetir":
		@$sayi= $_POST["say"];
		for($i=1; $i<$sayi; $i++) :
			if($_FILES["dosya".$i]["name"]=="") :								
				echo '<div class="alert alert-danger mt-1">'.$i.'. sıradaki dosya yüklenmedi</div>';
			else:
				if ($_FILES["dosya".$i]["size"]> (1024*1024*5)) :
					echo '<div class="alert alert-danger mt-1">'.$i.'. sıradaki dosyanın boyutu çok büyük</div>';
				else:
					$izinverilen=array("image/png");
					if (!in_array($_FILES["dosya".$i]["type"],$izinverilen)) :
						echo '<div class="alert alert-danger mt-1">'.$i.'. sıradaki dosyanın tipi uygun değil</div>';	
					else:
						move_uploaded_file($_FILES["dosya".$i]["tmp_name"],'yuklenenler/'.$_FILES["dosya".$i]["name"]);
						echo '<div class="alert alert-success mt-1">'.$i.'. sıradaki dosya yüklendi</div>';					
					endif;
				endif;
			endif;
		endfor;
	break; // upload yapılıyorr
	case "txtekle":
		$metin=$_POST["metin"];
		$dosyaad=$_POST["dosyaad"];
		$klasoryolu=$_POST["klasoryolu"];
 
 		$dt = fopen($klasoryolu.'/'.$dosyaad.'.txt','w');
		fwrite($dt,$metin);
		fclose($dt);
		
		echo '<div class="alert alert-success mt-5">Txt dosyası oluşturuldu</div> <br>';
		echo ' <input type="button" id="tekrartxt" value="Başka yüklemek ister misin ?" class="btn btn-danger mt-2" /> ';
 	break;
 	case "wordekle":
		$metin=$_POST["metin"];
		$dosyaad=$_POST["dosyaad"];
		$klasoryolu=$_POST["klasoryolu"];
 
 		$dt = fopen($klasoryolu.'/'.$dosyaad.'.doc','w');
		fwrite($dt,$metin);
		fclose($dt);
		
		echo '<div class="alert alert-success mt-5">Word dosyası oluşturuldu</div> <br>';
 		echo ' <input type="button" id="wordtxt" value="Başka yüklemek ister misin ?" class="btn btn-danger mt-2" /> ';
 	break;
 	case "klasorolustur":
		$klasorad=$_POST["klasorad"];
		mkdir($klasorad,0755,true);

		echo '<div class="alert alert-light mt-2 font-weight-bold" border border-dark><span class="text-danger">'.$klasorad.'</span> isminde klasör oluşturuldu.</div>  ';
	break;
	case "silmeharekati":
		if (!$_POST):
			echo "posttan gelmiyorsun";
		else:
			@$dosyaad=$_POST["dosyaad"];
			@$klasorad=$_POST["klasorad"];		
				
			if ($klasorad==$dosyaad) :
				$dosyasayi=0;

		 		foreach(scandir($klasorad) as $ic):		  
					 if ($ic=='.' || $ic=='..') 
				 	continue;
					$dosyasayi++; 
				endforeach;					
				
				if ($dosyasayi!=0) :
					echo '<div class="alert alert-danger mt-2">Bu klasör boş değil, Herşeyi Silelim mi?</div>';
						 
					echo '<div class="alert alert-warning mt-2" id="sildir">
					<a class="m-3 text-danger"  sectionId="'.$dosyaad.'" sectionId2="'.$klasorad.'">X</a>
					    <a class="m-3 text-danger" sectionId="vazgec" sectionId2="vazgec">Vazgeç</a>  	
					</div>';
				else:
					@rmdir($klasorad);
					echo '<div class="alert alert-success">KLASÖR SİLİNDİ</div>';
				endif;	  
			else:
				unlink($klasorad.'/'.$dosyaad);
				echo '<div class="alert alert-success">Dosya Silindi.</div>';
			endif;
		endif;
	break;
	case "dosyalar":
		$dosyalar=scandir(".");
		$klasorsayi=0;
		$dosyasayi=0;

	  	foreach($dosyalar as $dosya):
  			if ($dosya=='.' || $dosya=='..' ||$dosya=='hareket.php' || $dosya=='index.php' || $dosya=='load.gif') 
	 		continue;
	 		echo '<div class="alert alert-info">'.$dosya.' <a class="m-3 text-danger" sectionId="'.$dosya.'" sectionId2="'.$dosya.'">X</a></div>';
	 		$klasorsayi++;

			foreach(scandir($dosya) as $ic):
				if ($ic=='.' || $ic=='..') 
			 		continue;
			 		echo $ic.'<br>';
			 		$dosyasayi++;		 					
		 	endforeach;

		  	echo '<kbd>'.$dosyasayi.' Adet dosya var</kbd><br>';
			$dosyasayi=0;
		 	echo '<div id="dosyasonuc'.$dosya.'"></div>';
		  	echo "<hr>";
	  	endforeach;

	   	echo '<div class="alert alert-danger">Toplam '.$klasorsayi.' adet klasör var</div>';
	break;
	endswitch;
?>
</body>
</html>