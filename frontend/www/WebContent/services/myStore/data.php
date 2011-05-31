<?php
require_once dirname(__FILE__).'/Application.class.php';
$Apps	= Array();
$Apps['all']			= Array();
$Apps['news']			= Array();
$Apps['entertainment']	= Array();
$Apps['sport']			= Array();
$Apps['culture']		= Array();
$Apps['other']			= Array();

$Apps['other'][]								= $Apps['all'][]	= new Application('myProfile'		,4.9	,
			'' , true);
$Apps['entertainment'][]						= $Apps['all'][]	= new Application('myFriends'		,4.5	,
			'' , true);
$Apps['news'][]									= $Apps['all'][]	= new Application('myJam'			,4.0	, 
			'Un utilisateur informe le système de la présence d\'un embouteillage. Le module GPS fournit les coordonées au service myJam '.
			'qui avertira automatiquement tous les utilisateurs ayant souscrits à ce service et se trouvant à proximité de la zone congestionnée. '.
			'Le même service peut aussi être utilisé dans le cas d\'un accident.', true);
$Apps['other'][]								= $Apps['all'][]	= new Application('myTransport'		,4.2	, 
			'Service de covoiturage public/privé. Une solution envisagée - développée en collaboration avec la société VuLog, '.
			'spécialiste de solutions innovantes de mobilité urbaine - consiste à permettre aux utilisateurs de louer une voiture électrique '.
			'dans n\'importe quel endroit de la ville, et ce, même en «&#160;last minute&#160;».', true);
$Apps['culture'][]								= $Apps['all'][]	= new Application('myTranslator'	,3.8	,
			'Un service de traduction simultanée «&#160;non automatique&#160;» est offert par les usagers de myMed. '.
			'L\'utilisateur notifie le texte à traduire et les participants au réseau social, qui sont inscrits à ce service, '.
			'reçoivent une requête de traduction. La confiance entre traducteurs assurera la qualité de la traduction.', true);
$Apps['news'][]									= $Apps['all'][]	= new Application('myJob'			,3.8	,
			'En utilisant le service myTranslator, les offres et les demandes de travail sont publiées et misees en correspondance dans leurs langues maternelles. '.
			'(ex. «&#160;Esperto in impianti di riscaldamento eco-compatibili cerca lavoro entro 60 km da Cuneo&#160;» vs. '.
			'«&#160;PME spécialisée en constructions biocompatibles recherche personnel qualifié&#160;»).', true);
$Apps['other'][]								= $Apps['all'][]	= new Application('myMe'			,1.9	,
			'Le service publie les coordonnées de l\'utilisateur à tous ou partie de ses amis, qui pourront choisir de le suivre en temps réel. '.
			'myMe est particulièrement utile pour les enfants ou les personnes âgées et pendant les excursions en montagne ou en mer. '.
			'myMe fonctionne aussi en différé (ex. «&#160;Ton ami Pierre se trouve à Nice comme toi&#160;»).');
$Apps['other'][]								= $Apps['all'][]	= new Application('myMenu'			,1.9	, 
			'Un restaurant annonce aux passants et automobilistes dotés du service myMenu le menu du jour à un prix spécial. Cette information, '.
			'reçue par l\'utilisateur, sera relayée automatiquement et instantanément à tous les utilisateurs du service.');
$Apps['other'][]								= $Apps['all'][]	= new Application('myLocalProducer'	,1.9	,
			'Un service d\'approvisionnement en filière courte («&#160;kilomètres zéro&#160;») va permettre aux petits exploitants et aux petites entreprises de proposer '.
			'et vendre directement aux clients abonnés à ce service leurs produits bio et du terroir.');
$Apps['entertainment'][]						= $Apps['all'][]	= new Application('myWebGames'		,1.9	,
			'Un service pour rechercher des jeux sur Internet. myWebGame vas rechercher sur les grand sites de jeux en ligne et mettre à disposition ces jeux en un seul service');
$Apps['culture'][]								= $Apps['all'][]	= new Application('myMuseum'		,2.2	,
			'Un service pour lister les musée à proximité.');
$Apps['other'][]								= $Apps['all'][]	= new Application('myCitizen'		,2.5	,
			'Un service regroupant les différents sites de l\'administration française');
$Apps['culture'][]	= $Apps['entertainment'][]	= $Apps['all'][]	= new Application('myEbooks'		,0.5	,
			'Un service de recherche et de téléchargement de livres électroniques, les livre payant pourront être acheté directement depuis ce service.');
$Apps['sport'][]								= $Apps['all'][]	= new Application('myTrails'		,1.0	,
			'Un service pour trouver ou faire sa prochaine randonnée. A partir d\'un lieu, myTrails listera les randonnées possible en fonction de la difficulté '.
			'et de la periode dans l\'année');
$Apps['sport'][]								= $Apps['all'][]	= new Application('mySki'			,4.6	,
			'Un service pour trouver ou faire sa prochaine sortie neige. A partir d\'un lieu, mySki listera les randonnées '.
			'et les stations de ski possible en fonction de la difficulté et du type de materiel de glisse');
$Apps['other'][]								= $Apps['all'][]	= new Application('myBarter'		,4.4	,
			'Un service de troc en ligne. myBarter permet d\'échanger vos vêtement, jeux, outils... dont vous ne vous servez plus avec d\'autre personne.');







$translate	= Array();
$translate['all']			= 'tous';
$translate['news']			= 'actualités';
$translate['entertainment']	= 'sivertissement';
$translate['sport']			= 'sport';
$translate['culture']		= 'culture';
$translate['other']			= 'autres';
?>