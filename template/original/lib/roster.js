function ctRoster(usrName)
{
	var collection = 'Name: <strong>' +usrName+ '</strong>';

	document.getElementById('rosterData').innerHTML = collection;
}
function ctRosterClean()
{
	document.getElementById('rosterData').innerHTML = 'Hover over a player';
}