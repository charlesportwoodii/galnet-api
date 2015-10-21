# Galnet News

All Galnet news entries can be retrieved via the ```/``` endpoint, which supports pagination via the ```page``` GET parameter. Entries can also be retrieved by Galnet date by specifying the ```date``` GET parameter in ```DD-MMM-YYYY``` format.

## Examples

```
GET /
/GET /?page=5
```

```
{
	"data": [
		{
			"uid": "5624c54d9657ba260699dc70",
			"tite": "Pilots Call for Starship Lighting Enquiry",
			"content": "More than 220,000 pilots have signed a petition calling for an inquiry into the lack of anti-collision lights on starships and emergency lighting on system-authority ships. Jenson Zanetti, chairman of the Pilots Federation Safety Board, stated:\n\"At some point in their careers, most pilots will be struck by an authority or private vessel they did not see, which can be disastrous for both parties. If our proposals are implemented, we predict a massive decrease in the number of fatal collisions in and around space stations.\"\nThe petition calls for all system-authority vessels to be fitted with lighting equipment to help pilots identify these important craft, and for anti-collision lights to be fitted to all starships. The petition concludes by stating that the lights only need to be used within a station's no-fire zone, and when the ship's main lights are active.\nCommander Devenish, Alliance News Network",
			"published": 1445230800,
			"galnet_publication_time": 42027487200,
			"url": "https://community.elitedangerous.com/galnet/uid/5624c54d9657ba260699dc70"
		},
		{
			"uid": "5624b01c9657ba260699dc6f",
			"tite": "GalNet Weekly Economic Report",
			"content": "In this weekly digest, the latest GalNet data is compiled to show the economic state of a short list of minor factions and the star systems they inhabit.\nHere are 10 of the 11,041 minor factions currently enjoying an economic boom:\nPeople's Meldrith Values Party\nMovement for LHS 2429 Democrats\nAllied LHS 380 Law Party\nIsita Confederation\nTefenhua Alliance Union\nBharu Silver Public Partners\nGakiutl Blue Mafia\nCarthage Silver Drug Empire\nMaitis Energy Company\nTheta Indi Advanced Incorporated\nWhen in boom, the wealth of a system is increased for the duration and all trade missions have double the effect on influence. Boom can also positively increase a minor faction's influence. Boom can be entered by consistent trade profits and completed trade contracts. Booms tend to last until they naturally expire or until some other indicator takes precedence, such as famine.\nData is correct at time of publishing.",
			"published": 1445230800,
			"galnet_publication_time": 42027487200,
			"url": "https://community.elitedangerous.com/galnet/uid/5624b01c9657ba260699dc6f"
		},
		{...},
	]
}
```