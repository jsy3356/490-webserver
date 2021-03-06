<?php 
session_start(); 

// Check if username is set, if not, go to login page.
if (!isset($_SESSION['username'])) 
{
	$_SESSION['msg'] = "You must log in first";
	header('location: login.php');
}

// Handles logout, sends back to login page
if (isset($_GET['logout'])) 
{
	session_destroy();
	unset($_SESSION['username']);
	header("location: login.php");
}

require_once('scripts.php');
?>

<html>

<!-- CSS -->

<style>
.center {
	display: block;
	margin-left: auto;
	margin-right: auto;
	width: 50%;
}
img { border-radius: 10px; }
table { border-radius: 10px; margin:auto; }
th {
	border-radius: 10px;
	text-align: center;
	padding-top: 12px;
	padding-bottom: 12px;
	background-color: #4CAF50;
	color: white;
}
td { border-radius: 10px; border: 1px solid #ddd; padding: 8px; }
tr:nth-child(even) { border-radius: 10px; background-color: #f2f2f2; }
td:nth-child(odd) { text-align:right; }
</style>

<!-- Index Page - All searches are done here -->

<h1>Liquor Cabinet</h1>

<!-- Nav options -->
<?php  if (isset($_SESSION['username'])) : ?>
	<p>Logged in as: <strong><?php echo $_SESSION['username']; ?></strong></p>
	<p><a href="profile.php">your profile</a></p>
	<p><a href="index.php?logout='1'" style="color: red;">logout</a></p>
<?php endif ?>

<hr>

<!--
Regular search
Returns a list of drinks with all their information displayed already.
-->
<body>
<h3>Search</h3>
<p>Lookup drinks by name or first letter.</p>
<p>Lookup ingredient by name.</p>
<p>Lookup recipes with ingredient combinations. ( EX: rum,vodka,gin )</p>
<select name="search type" id="searchType">
	<option value="search.php?s=">Drink name</option>
	<option value="search.php?f=">First letter</option>
	<option value="search.php?i=">Ingredient</option>
	<option value="filter.php?i=">Ingredient-Combo</option>
</select>
<input type="text" placeholder="Enter your search keywords" id="mySearch" />
<button onclick="DoSearch()">Search</button>

<hr>

<!--
Category search
The API doesn't do a good job of filtering multiple categories.
Will probably have to start shoving API data into our database so that we can filter it ourselves.
That, or we can make several different requests instead of one with each filter in the URL.. But that's greedy.
-->
<h3>Category Search</h3>
<p>Lookup specific drinks using available categories</p>
<!-- Alcoholic Filter -->
<select name="alcoholicFilter" id="alcoholicFilter">
	<option value="a=Alcoholic">Alcoholic</option>
	<option value="a=Non_Alcoholic">Non-Alcoholic</option>
	<option value="a=Optional_alcohol">Optional Alcohol</option>
</select>
<!-- Category Filter -->
<select name="categoryFilter" id="categoryFilter">
	<option value="">Any</option>
	<option value="c=Beer">Beer</option>
	<option value="c=Cocktail">Cocktail</option>
	<option value="c=Cocoa">Cocoa</option>
	<option value="c=Coffee_/_Tea">Coffee / Tea</option>
	<option value="c=Homemade_Liqueur">Homemade Liqueur</option>
	<option value="c=Ordinary_Drink">Ordinary Drink</option>
	<option value="c=Other/Unknown">Other/Unknown</option>
	<option value="c=Punch_/_Party_Drink">Punch / Party Drink</option>
	<option value="c=Shot">Shot</option>
	<option value="c=Soft_Drink_/_Soda">Soft Drink / Soda</option>
	<option value="c=milk_/_float_/_shake">Milk / Float / Shake</option>
</select>
<!-- Glass Filter -->
<select name="glassFilter" id="glassFilter">
	<option value="">Any</option>
	<option value="g=Balloon_Glass">Balloon Glass</option>
	<option value="g=Beer_mug">Beer Mug</option>
	<option value="g=Beer_pilsner">Beer Pilsner</option>
	<option value="g=Brandy_snifter">Brandy Snifter</option>
	<option value="g=Champagne_flute">Champagne Flute</option>
	<option value="g=Cocktail_glass">Cocktail Glass</option>
	<option value="g=Coffee_mug">Coffee Mug</option>
	<option value="g=Collins_glass">Collins Glass</option>
	<option value="g=Copper_Mug">Copper Mug</option>
	<option value="g=Cordial_glass">Cordial Glass</option>
	<option value="g=Coupe_Glass">Coupe Glass</option>
	<option value="g=Highball_glass">Highball Glass</option>
	<option value="g=Hurricane_glass">Hurricane Glass</option>
	<option value="g=Irish_coffee_cup">Irish Coffee Cup</option>
	<option value="g=Jar">Jar</option>
	<option value="g=Margarita/Coupette_glass">Margarita/Coupette Glass</option>
	<option value="g=Margarita_glass">Margarita Glass</option>
	<option value="g=Martini_Glass">Martini Glass</option>
	<option value="g=Mason_jar">Mason Jar</option>
	<option value="g=Nick_and_Nora_Glass">Nick and Nora Glass</option>
	<option value="g=Old-fashioned_glass">Old-Fashioned Glass</option>
	<option value="g=Parfait_glass">Parfait Glass</option>
	<option value="g=Pint_glass">Pint Glass</option>
	<option value="g=Pitcher">Pitcher</option>
	<option value="g=Pousse_cafe_glass">Pousse Cafe Glass</option>
	<option value="g=Punch_bowl">Punch Bowl</option>
	<option value="g=Shot_glass">Shot Glass</option>
	<option value="g=Whiskey_sour_glass">Whiskey Sour Glass</option>
	<option value="g=White_wine_glass">White Wine Glass</option>
	<option value="g=Wine_Glass">Wine Glass</option>
</select>

<button onclick="DoFilter()">Search</button>

<hr>

<!--
List will basically load up some results determined by the API. Nothing fancy.
-->
<h3>List</h3>
<p>Pull up a catered list of drinks</p>
<select name="list type" id="listType">
	<option value="random.php">1 Random</option>
	<option value="randomselection.php">10 Random</option>
	<option value="popular.php">Popular</option>
	<option value="latest.php">Latest</option>
	<option value="list.php?c=list">Categories</option>
	<option value="list.php?g=list">Glasses</option>
	<option value="list.php?i=list">Ingredients</option>
	<option value="list.php?a=list">Alcoholic Filters</option>
</select>
<button onclick="DoList()">List</button>

<hr>

<!--
ID Lookup does exactly what it says; the API has IDs for each cocktail & ingredient
-->
<h3>ID Lookup</h3>
<p>Find a drink recipe or an ingredient's info from its ID</p>
<select name="lookup type" id="lookupType">
	<option value="i">Drink ID</option>
	<option value="iid">Ingredient ID</option>
</select>
<input type="text" placeholder="Enter your search keywords" id="myLookup" />
<button onclick="DoLookup()">Search</button>

<hr>

<!--
Search results show up here. They are inserted into the div searchResponse.
May get rid of the button to clear results, as it's not really needed.
-->
<button style="font-size: 150%; width:100%;" onclick="ClearResults()">Clear Results</button>
<br>
<div id="searchResponse"> </div>
</body>
</html>
