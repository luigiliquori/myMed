<? include("header.php"); ?>

<script type="text/javascript">

	//srednia
	var average =0;
	
	//ilosc glosow
	var votes = 0;
	
  //po ustawieniu nowej oceny ta metoda wykona sie
  function setRank(){
  
  	  //zmiana ilosci glosow
	  votes++;
	  setVotes(votes);
  
	  var newValue = $(".Rate").text();	
	  
	  //ustawienie nowe wartosci average
	  
	  average = (average+parseInt(newValue))/votes;
	  
	  $(".starsAverage").attr("id",average);
	  
	  //wyswietlenie gwiazdek
	  setNewAvaregeStars()
	  
	  //usuniecie przycisku do oceniania
	  $("#buttonToVote").remove();
	  

	  
	  //tutaj mozesz jakos wyswietlic, ze zostalo juz zaglosowane
	  
	  //zamkniecie okna 
	  $.fn.colorbox.close()
  
  }
  
  //wyswietlenie gwiazdek average
  function setNewAvaregeStars(){
  
	  //usuniecie starych gwiazdek
	  $(".starsAverage").empty();
	  
	  //wyswietlenie jeszcze raz gwiazdek - update
	  $(".starsAverage").jRating({
		  isDisabled : true,
		  showRateInfo : true,
		  step : true,
		  length : 6,
		  rateMax : 6,
		  onSuccess : function(){
			setRank();
		  },
		});
  
  }
  
  //wyswietlenie glosow
  function setVotes(newVotes){
  
	  //usuniecie starych glosow
	  $(".votes").empty();
	  
	  //wyswietlenie jeszcze raz glosow - update
	  $(".votes").append(newVotes);
  
  }


  $(document).ready(function(){
	
	//var average = session.getAttribrute("some_parameter"); --tutaj pobranie z sesji/request sredniej oceny
	//var votes = session.getAttribrute("some_parameter2"); --tutaj pobranie z sesji/request ilosci oddanych glosow
	
	//poczatkowe ustawienie sredniej
	$(".starsAverage").attr("id",average);
	
	//ustawienie gwiazdek - average
    setNewAvaregeStars()
	
	//ustawienie ilosci glosow
	  setVotes(votes);
	
	
	
	//ustawienie gwiazdek - do oceny
	$(".starsRank").jRating({
	  showRateInfo : true,
	  step : true,
	  length : 6,
	  rateMax : 6,
	  
	});
	
	//ustawienie colorboxa
	$(".inline").colorbox({inline:true, width:"20%",transition : "none"});
	
  });
  

</script>



rank:
	
	<!-- tutaj jest glupio, id= oznacza liczbe juz zaznaczonych gwazdek, czyli srednia-->
	<!-- w javiescripcie (powyzej) jest ustawiona zmienna average gdzie trzeba ustawic ta srednia, jesli wczesniej pobierzesz z bazy danych w php to mozna pobrac ja jako z parametru sesji/request-->
	<div class="starsAverage"></div>
	nb of votes:
	<div class="votes"></div>



<!-- przycisk do oceny-->
<input type="button" id="buttonToVote" class='inline' value = "Rank me!" href="#inline_content"  />

<!-- ukryte gwiazdki do ustawienia -->
		<div style='display:none'>
			<div id='inline_content' style='padding:10px; background:#fff;'>
				Please set Your rank!
				<div class="starsRank" id='0' ></div>
				<!-- tu jest wpisywana wartosc-->
				<div style='display:none' class='Rate'></div>
				<!-- ten guzik mozna usunac spokojnie, byl mi tylko potrzebny czy dziala funkcja setRank(), nie sprawdzilem tego bo nie mam php, jest ona wywolywana automatycznie po zaznaczeniu gwiazdek-->
				<input type="button" class='inline' value = "Rank me!" href="#inline_content" onClick='setRank()' />
			</div>
		</div>


</body>

</html>