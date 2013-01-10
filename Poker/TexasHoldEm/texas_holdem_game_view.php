<!DOCTYPE HTML>
<html>
    <head>
        <style>
            body
            {
                margin: 0px;
                padding: 0px;
            }
            #test
            {
                width: 1024px;
                height: 768px;
                /*margin: 0px auto;*/
                border: 1px solid red;
            }
        </style>
    </head>
    <body>
		<button id="buttonFold">
            <img src="/pix/web_graphics/free_website_graphics/icons/books/book13.gif" alt="Read book" /><br/>Fold
        </button>
        <button id="buttonCheck">
            <img src="/pix/web_graphics/free_website_graphics/icons/books/book13.gif" alt="Read book" /><br/>Check
        </button>
		<button id="buttonCall">
            <img src="/pix/web_graphics/free_website_graphics/icons/books/book13.gif" alt="Read book" /><br/>Call
        </button>
        <button id="buttonRaise">
            <img src="/pix/web_graphics/free_website_graphics/icons/books/book13.gif" alt="Read book" /><br/>Raise
        </button>
        <button id="buttonReset">
            <img src="/pix/web_graphics/free_website_graphics/icons/books/book13.gif" alt="Read book" /><br/>Reset Game
        </button>
        <button id="buttonPause">
            <img src="/pix/web_graphics/free_website_graphics/icons/books/book13.gif" alt="Read book" /><br/>Pause Game
        </button>
        <button id="buttonResume">
            <img src="/pix/web_graphics/free_website_graphics/icons/books/book13.gif" alt="Read book" /><br/>Resume Game
        </button>
        <div id="test">
            <canvas id="myCanvas" width=1024 height=768></canvas>
        </div>
        <script>
            //tested and developed using chrome Version 23.0.1271.64 m 2012-11-14

			//get handles to all controls
			var myCanvas = document.getElementById("myCanvas");
            var myContext = myCanvas.getContext("2d");

			var buttonFold = document.getElementById("buttonFold");
			var buttonReset = document.getElementById("buttonReset");
			var buttonResume = document.getElementById("buttonResume");
			var buttonPause = document.getElementById("buttonPause");

			var buttonCheck = document.getElementById("buttonCheck");
			var buttonCall = document.getElementById("buttonCall");
			var buttonRaise = document.getElementById("buttonRaise");

			buttonFold.style.visibility = "hidden";
			buttonCheck.style.visibility = "hidden";
			buttonCall.style.visibility = "hidden";
			buttonRaise.style.visibility = "hidden";

			//define help functions
            function Distance(x1, y1, x2, y2)
            {
                return Math.sqrt((x1-x2)*(x1-x2) + (y1-y2)*(y1-y2));
            }

            //card suits
            var CardSuit = {
                Spade: "\u2660",
                Heart: "\u2665",
                Club: "\u2663",
                Diamond: "\u2666",
                Hidden: "?"
            }

            //card values
            var CardValue = {
                Two: 2,
                Three: 3,
                Four: 4,
                Five: 5,
                Six: 6,
                Seven: 7,
                Eight: 8,
                Nine: 9,
                Ten: 10,
                Jack: 11,
                Queen: 12,
                King: 13,
                Ace: 14,
                Hidden: "?"
            }

            //card class
            function Card(s, v)
            {
                this.suit = s;
                this.value = v;
                this.x = 0.0;
                this.y = 0.0;
                this.w = 75.0;
                this.h = 100.0;
                this.tx = 0.0;
                this.ty = 0.0;

                function toString()
                {
                    return this.suit + ":" + this.value;
                }
                function Draw(g)
                {
                    //draw card background
                    g.beginPath();
                    g.rect(this.x, this.y, this.w, this.h);
                    g.fillStyle = "white";
                    g.fill();
                    g.lineWidth = 1;
                    g.strokeStyle = "black";
                    g.stroke();

                    //draw card content
                    g.textBaseline = "top";
                    g.font = "32pt Arial";

					var valueText = "";

                    if(this.suit == CardSuit.Spade)
                    {
						g.strokeStyle = "black";
						g.fillStyle = "black";
						g.fillText(this.suit, this.x + 26, this.y + 24);
                    }
                    else if(this.suit == CardSuit.Heart)
                    {
						g.strokeStyle = "red";
                        g.fillStyle = "red";
                        g.fillText(this.suit, this.x + 24, this.y + 24);
                    }
                    else if(this.suit == CardSuit.Club)
                    {
						g.strokeStyle = "black";
                        g.fillStyle = "black";
                        g.fillText(this.suit, this.x + 24, this.y + 24);
                    }
                    else if(this.suit == CardSuit.Diamond)
                    {
						g.strokeStyle = "red";
                        g.fillStyle = "red";
                        g.fillText(this.suit, this.x + 27, this.y + 23);
                    }
                    else if(this.suit == CardSuit.Hidden)
                    {
						g.fillStyle = "black";
                        g.strokeStyle = "black";
                        g.strokeText(this.suit, this.x + 25, this.y + 24);
                    }
                    else
                    {
                        throw "Card: error invalid suit value";
                    }

					//top left
                    g.font = "10pt Arial";
                    g.strokeText(this.value, this.x + 5, this.y + 5);

					//rotated bottom right
                    g.rotate(Math.PI);
                    g.strokeText(this.value, -this.x - 68, -this.y - 95);
                    g.rotate(Math.PI);
                }
                function Update()
                {
                    if(Distance(this.x, this.y, this.tx, this.ty) < 0.1)
                    {
                        this.x = this.tx;
                        this.y = this.ty;
                    }

					var dx = (this.tx - this.x);
					var dy = (this.ty - this.y);

					//var unitDX = dx / Math.abs(dx);
					//var unitDY = dy / Math.abs(dy);

                    this.x = this.x + dx * 0.1;
                    this.y = this.y + dy * 0.1;
                }
                return {
                    "toString": toString,
                    "Draw": Draw,
                    "Update": Update,
                    "x": this.x,
                    "y": this.y,
                    "w": this.w,
                    "h": this.h,
                    "suit": this.suit,
                    "value": this.value,
                    "tx": this.tx,
                    "ty": this.ty
                }
            }

            //deck class
            function Deck()
            {
                this.cards = null;

                function Reset()
                {
					//reset to typical 52 cards
                    this.cards = new Array();
                    this.cards.push( new Card(CardSuit.Spade, CardValue.Two) );
                    this.cards.push( new Card(CardSuit.Spade, CardValue.Three) );
                    this.cards.push( new Card(CardSuit.Spade, CardValue.Four) );
                    this.cards.push( new Card(CardSuit.Spade, CardValue.Five) );
                    this.cards.push( new Card(CardSuit.Spade, CardValue.Six) );
                    this.cards.push( new Card(CardSuit.Spade, CardValue.Seven) );
                    this.cards.push( new Card(CardSuit.Spade, CardValue.Eight) );
                    this.cards.push( new Card(CardSuit.Spade, CardValue.Nine) );
                    this.cards.push( new Card(CardSuit.Spade, CardValue.Ten) );
                    this.cards.push( new Card(CardSuit.Spade, CardValue.Jack) );
                    this.cards.push( new Card(CardSuit.Spade, CardValue.Queen) );
                    this.cards.push( new Card(CardSuit.Spade, CardValue.King) );
                    this.cards.push( new Card(CardSuit.Spade, CardValue.Ace) );

                    this.cards.push( new Card(CardSuit.Heart, CardValue.Two) );
                    this.cards.push( new Card(CardSuit.Heart, CardValue.Three) );
                    this.cards.push( new Card(CardSuit.Heart, CardValue.Four) );
                    this.cards.push( new Card(CardSuit.Heart, CardValue.Five) );
                    this.cards.push( new Card(CardSuit.Heart, CardValue.Six) );
                    this.cards.push( new Card(CardSuit.Heart, CardValue.Seven) );
                    this.cards.push( new Card(CardSuit.Heart, CardValue.Eight) );
                    this.cards.push( new Card(CardSuit.Heart, CardValue.Nine) );
                    this.cards.push( new Card(CardSuit.Heart, CardValue.Ten) );
                    this.cards.push( new Card(CardSuit.Heart, CardValue.Jack) );
                    this.cards.push( new Card(CardSuit.Heart, CardValue.Queen) );
                    this.cards.push( new Card(CardSuit.Heart, CardValue.King) );
                    this.cards.push( new Card(CardSuit.Heart, CardValue.Ace) );

                    this.cards.push( new Card(CardSuit.Club, CardValue.Two) );
                    this.cards.push( new Card(CardSuit.Club, CardValue.Three) );
                    this.cards.push( new Card(CardSuit.Club, CardValue.Four) );
                    this.cards.push( new Card(CardSuit.Club, CardValue.Five) );
                    this.cards.push( new Card(CardSuit.Club, CardValue.Six) );
                    this.cards.push( new Card(CardSuit.Club, CardValue.Seven) );
                    this.cards.push( new Card(CardSuit.Club, CardValue.Eight) );
                    this.cards.push( new Card(CardSuit.Club, CardValue.Nine) );
                    this.cards.push( new Card(CardSuit.Club, CardValue.Ten) );
                    this.cards.push( new Card(CardSuit.Club, CardValue.Jack) );
                    this.cards.push( new Card(CardSuit.Club, CardValue.Queen) );
                    this.cards.push( new Card(CardSuit.Club, CardValue.King) );
                    this.cards.push( new Card(CardSuit.Club, CardValue.Ace) );

                    this.cards.push( new Card(CardSuit.Diamond, CardValue.Two) );
                    this.cards.push( new Card(CardSuit.Diamond, CardValue.Three) );
                    this.cards.push( new Card(CardSuit.Diamond, CardValue.Four) );
                    this.cards.push( new Card(CardSuit.Diamond, CardValue.Five) );
                    this.cards.push( new Card(CardSuit.Diamond, CardValue.Six) );
                    this.cards.push( new Card(CardSuit.Diamond, CardValue.Seven) );
                    this.cards.push( new Card(CardSuit.Diamond, CardValue.Eight) );
                    this.cards.push( new Card(CardSuit.Diamond, CardValue.Nine) );
                    this.cards.push( new Card(CardSuit.Diamond, CardValue.Ten) );
                    this.cards.push( new Card(CardSuit.Diamond, CardValue.Jack) );
                    this.cards.push( new Card(CardSuit.Diamond, CardValue.Queen) );
                    this.cards.push( new Card(CardSuit.Diamond, CardValue.King) );
                    this.cards.push( new Card(CardSuit.Diamond, CardValue.Ace) );
                }
				function Shuffle()
				{
					this.cards.sort(function(){
						return Math.random() > 0.5;
					});
				}
                function AlignAll()
                {
					//set so that all cards are displayed
                    var offX = 0.0;
                    var offY = 0.0;

                    for(var ia=0; ia<this.cards.length; ia++)
                    {
                        this.cards[ia].tx = offX;
                        this.cards[ia].ty = offY;

                        offX += this.cards[ia].w;

                        if(ia % 13 == 12)
                        {
                            offY += this.cards[ia].h;
                            offX = 0;
                        }
                    }
                }
                function DrawAll(g)
                {
					//draw all cards in deck
                    for(var ia=0; ia<this.cards.length; ia++)
                    this.cards[ia].Draw(g);
                }
                function UpdateAll()
                {
					//all cards move into place immediatly
                    for(var ia=0; ia<this.cards.length; ia++)
                    this.cards[ia].Update();
                }
                function UpdateOne()
                {
					//one card move into place at a time
                    for(var ia=0; ia<this.cards.length; ia++)
                    {
                        if(this.cards[ia].x != this.cards[ia].tx || this.cards[ia].y != this.cards[ia].ty)
                        {
                            this.cards[ia].Update();
                            break;
                        }
                    }
                }
                return {
                    "Reset": Reset,
                    "AlignAll": AlignAll,
                    "DrawAll": DrawAll,
                    "UpdateAll": UpdateAll,
                    "UpdateOne": UpdateOne,
					"Shuffle": Shuffle,
                    "cards": this.cards
                }
            }

			var PlayerChoice = {
				Folded: 0,
				Checked: 1,
				Called: 2,
				Raised: 3
			}

			//the player base class
            function Player()
            {
				this.money = 0.0;
				this.bet = 0.0;
				this.name = 0.0;
				this.isReady = false;
				this.roundChoice = null;
				this.cards = null;

				return {
					"money": this.money,
					"bet": this.bet,
					"isReady": this.isReady,
					"roundChoice": this.roundChoice
				}
            }

			//the human player class
			//handles the interaction between model and user
			function HumanPlayer()
			{
				var basePlayer = new Player();

				function AskBlind(blindAmount)
				{
					var betAmount = blindAmount;
					this.money -= betAmount;
					this.bet += betAmount;
					this.isReady = true;
				}
				function AskBet(minimumAmount)
				{
					this.isReady = false;
					this.roundChoice = null;

					if(minimumAmount == this.bet)
						buttonCheck.style.visibility = "visible";
					else if(minimumAmount > this.bet)
						buttonCall.style.visibility = "visible";

					buttonRaise.style.visibility = "visible";
					buttonFold.style.visibility = "visible";

					var p = this;

					buttonFold.onclick = function()
					{
						p.roundChoice = PlayerChoice.Folded;
						p.isReady = true;

						buttonFold.style.visibility = "hidden";
						buttonCheck.style.visibility = "hidden";
						buttonCall.style.visibility = "hidden";
						buttonRaise.style.visibility = "hidden";
					}

					buttonCheck.onclick = function()
					{
						p.roundChoice = PlayerChoice.Checked;
						p.isReady = true;

						buttonFold.style.visibility = "hidden";
						buttonCheck.style.visibility = "hidden";
						buttonCall.style.visibility = "hidden";
						buttonRaise.style.visibility = "hidden";
					}

					buttonCall.onclick = function()
					{
						p.roundChoice = PlayerChoice.Called;
						var betAmount = minimumAmount;
						p.money -= betAmount;
						p.bet += betAmount;
						p.isReady = true;

						buttonFold.style.visibility = "hidden";
						buttonCheck.style.visibility = "hidden";
						buttonCall.style.visibility = "hidden";
						buttonRaise.style.visibility = "hidden";
					}

					buttonRaise.onclick = function()
					{
						p.roundChoice = PlayerChoice.Raised;
						var betAmount = minimumAmount + 10.0;
						p.money -= betAmount;
						p.bet += betAmount;
						p.isReady = true;

						buttonFold.style.visibility = "hidden";
						buttonCheck.style.visibility = "hidden";
						buttonCall.style.visibility = "hidden";
						buttonRaise.style.visibility = "hidden";
					}
				}
				return {
					"money": basePlayer.money,
					"bet": basePlayer.bet,
					"isReady": basePlayer.isReady,
					"roundChoice": basePlayer.roundChoice,
					"AskBlind": AskBlind,
					"AskBet": AskBet
				}
			}

			//the computer player class
			//handles the ineraction between model and simulated players
			function ComputerPlayer()
			{
				var basePlayer = new Player();

				function AskBlind(blindAmount)
				{
					var betAmount = blindAmount;
					this.money -= betAmount;
					this.bet += betAmount;
					this.isReady = true;
				}
				function AskBet(minimumAmount)
				{
					this.isReady = false;

					var betAmount = 0.0;

					if(minimumAmount > this.bet)
					{
						betAmount = minimumAmount - this.bet;
						this.roundChoice = PlayerChoice.Called;
					}
					else
					{
						this.roundChoice = PlayerChoice.Checked;
					}

					this.money -= betAmount;
					this.bet += betAmount;
					this.isReady = true;
				}
				return {
					"money": basePlayer.money,
					"bet": basePlayer.bet,
					"isReady": basePlayer.isReady,
					"roundChoice": basePlayer.roundChoice,
					"AskBlind": AskBlind,
					"AskBet": AskBet
				}
			}

			//a seat/slot at the game table
			//used only for the graphics
            function PlayerSlot(x, y)
            {
                this.x = x;
                this.y = y;
                this.w = 50.0;
                this.h = 50.0;
				this.player = null;
				this.isDealer = false;
				this.isOccupied = false;

                function Draw(g)
                {
                    //draw seat background
                    g.beginPath();
                    g.rect(this.x, this.y, this.w, this.h);
                    g.fillStyle = 'white';
                    g.fill();
                    g.lineWidth = 1;
                    g.strokeStyle = 'black';
                    g.stroke();

					//draw other stuff
                    if(this.isOccupied == true)
                    {
						g.textBaseline = "top";
						g.font = "10pt Arial";
                        g.strokeStyle = "black";
                        g.strokeText(this.player.money, this.x + 5, this.y + 24);

						g.textBaseline = "top";
						g.font = "10pt Arial";
                        g.strokeStyle = "black";
                        g.strokeText(this.player.bet, this.x + 5, this.y + 100);
					}
                }
                return {
                    "isOccupied": this.isOccupied,
					"isDealer": this.isDealer,
                    "x": this.x,
                    "y": this.y,
                    "w": this.w,
                    "h": this.h,
                    "Draw": Draw,
					"player": this.player
                }
            }

			function ChipMoney(x, y)
			{
				this.x = x;
                this.y = y;
				this.money = 0.0;

				function Draw(g)
				{
					g.textBaseline = "top";
					g.font = "10pt Arial";
                    g.strokeStyle = "black";
                    g.strokeText(this.money, this.x, this.y);
				}

				return {
					"x": this.x,
                    "y": this.y,
					"money": this.money,
					"Draw": Draw
				}
			}

			//the game class
			//handles the diffrent states and events of the game
            function Game()
            {
                var gameDeck = null;
                var playerSlots = null;
                var displayedCards = null;
				var playingPlayers = null;
				var communityCards = null;
				var roundCount = 0;
				var dealerIndex = 0;
				var blindIndex = 0;
				var anteIndex = 0;

				var stateCurrent = null;
				var stateNext = null;

				var stateIndex_DealPreFlop = null;

				var playerHighestBetting = null;
				var playerAskBet = null;

				var potMoney = null;

				var BlindAmount = new Array(
					10,
					20,
					40,
					80,
					100,
					200,
					300,
					400,
					500,
					600,
					700,
					800,
					900,
					1000
				)

                var GameState = {
					AskForBlinds: 0,
					AskForBets: 1,
					CheckBets: 2,
					PotEatBets: 2.1,
					S_AC: 3,
					S_AD: 4,
					DealPreFlop: 5,
					DealFlop: 6,
					DealTurn: 7,
					DealRiver: 8,
					FinalState: 9,
					S_F: 10,
					WaitForCards: 11,
					WaitForPlayers: 12
                }


				function WaitForPlayers()
				{
					//console.debug("game: state: WaitForPlayers");

					for(var ia=0; ia<playingPlayers.length; ia++)
					{
						if( playingPlayers[ia].isReady == false )
						{
							//console.debug("game: player not ready: " + ia);
							return;
						}
					}

					stateCurrent = stateNext.pop();
				}
				function WaitForCards()
				{
					//console.debug("game: state: WaitForCards");

					for(var ia=0; ia<displayedCards.length; ia++)
					{
						if( displayedCards[ia].x != displayedCards[ia].tx || displayedCards[ia].y != displayedCards[ia].ty )
						{
							//console.debug("game: card not ready: " + ia);
							return;
						}
					}

					stateCurrent = stateNext.pop();
				}
				function AskForBlinds()
				{
					console.debug("game: state: AskForBlinds");
					var bigBlindAmount = BlindAmount[blindIndex];

					//small blind
					playingPlayers[dealerIndex + 1].AskBlind(bigBlindAmount / 2.0);
					//big blind
					playingPlayers[dealerIndex + 2].AskBlind(bigBlindAmount);

					//TODO add handling for when player refuses to pay blind
					playerHighestBetting = playingPlayers[dealerIndex + 2];
					playerAskBet = playingPlayers[(dealerIndex + 3) % playingPlayers.length];

					stateNext.push(GameState.DealPreFlop);
					stateCurrent = GameState.AskForBets;
				}
				function AskForBets()
				{
					console.debug("game: state: AskForBets: " + playerAskBet);

					var askAmount = playerHighestBetting == null ? 0.0 : playerHighestBetting.bet;
					console.debug("askAmount: "+askAmount);
					playerAskBet.AskBet(askAmount);

					stateNext.push(GameState.CheckBets);
					stateCurrent = GameState.WaitForPlayers;
				}
				function CheckBets()
				{
					console.debug("game: state: CheckBets: " + playerAskBet);
					var playerAskNext = null;
					var playerIndex = playingPlayers.indexOf(playerAskBet);

					if(playerAskBet.roundChoice == PlayerChoice.Folded)
					{
						//remove player from playing players
						playingPlayers.splice(playerIndex, 1);

						//fold the cards for the player
						for(var ia=0; ia<playerAskBet.cards.length; ia++)
						{
							playerAskBet.cards[ia].tx = -100;
						}

						//ask next player in the now smaller list
						playerAskNext = playingPlayers[playerIndex % playingPlayers.length];
					}
					else if(playerAskBet.roundChoice == PlayerChoice.Checked || playerAskBet.roundChoice == PlayerChoice.Called || playerAskBet.roundChoice == PlayerChoice.Raised)
					{
						playerAskNext = playingPlayers[(playerIndex + 1)% playingPlayers.length];
					}
					else
					{
						throw "Game: error player didnt make a choice";
					}

					if(playerAskBet.roundChoice == PlayerChoice.Raised)
					{
						//set highest betting
						playerHighestBetting = playerAskBet;
					}

					if(playerHighestBetting == playerAskNext)
					{
						//then continue to next state
						playerAskBet = null;
						playerHighestBetting = null;
						stateCurrent = GameState.PotEatBets;
					}
					else
					{
						//then ask next player
						playerAskBet = playerAskNext;
						stateCurrent = GameState.AskForBets;
					}
				}
				function PotEatBets()
				{
					console.debug("Game: PotEatBets");

					for(var ia=0; ia<playerSlots.length; ia++)
					{
						if(playerSlots[ia].player != null)
						{
							var betMoney = playerSlots[ia].player.bet;
							playerSlots[ia].player.bet = 0;
							potMoney.money += betMoney;
						}
					}

					stateCurrent = stateNext.pop();
				}
                function DealPreFlop()
                {
					console.debug("game: state: DealPreFlop: " + stateIndex_DealPreFlop);

					//slot 0
					if(stateIndex_DealPreFlop == 0)
					{
						var c = gameDeck.cards.pop();
						playerSlots[0].player.cards.push(c);

						c.tx = 50;
						c.ty = 0;
						displayedCards.push(c);
					}
					else if(stateIndex_DealPreFlop == 4)
					{
						var c = gameDeck.cards.pop();
						playerSlots[0].player.cards.push(c);

						c.tx = 100;
						c.ty = 0;
						displayedCards.push(c);
					}
					//slot 1
					else if(stateIndex_DealPreFlop == 1)
					{
						var c = gameDeck.cards.pop();
						playerSlots[1].player.cards.push(c);

						var h = new Card(CardSuit.Hidden, CardValue.Hidden);
						h.tx = 300;
						h.ty = 0;
						displayedCards.push(h);
					}
					else if(stateIndex_DealPreFlop == 5)
					{
						var c = gameDeck.cards.pop();
						playerSlots[1].player.cards.push(c);

						var h = new Card(CardSuit.Hidden, CardValue.Hidden);
						h.tx = 350;
						h.ty = 0;
						displayedCards.push(h);
					}
					//slot 2
					else if(stateIndex_DealPreFlop == 2)
					{
						var c = gameDeck.cards.pop();
						playerSlots[2].player.cards.push(c);

						var h = new Card(CardSuit.Hidden, CardValue.Hidden);
						h.tx = 550;
						h.ty = 0;
						displayedCards.push(h);
					}
					else if(stateIndex_DealPreFlop == 6)
					{
						var c = gameDeck.cards.pop();
						playerSlots[2].player.cards.push(c);

						var h = new Card(CardSuit.Hidden, CardValue.Hidden);
						h.tx = 600;
						h.ty = 0;
						displayedCards.push(h);
					}
					//slot 3
					else if(stateIndex_DealPreFlop == 7)
					{
						var c = gameDeck.cards.pop();
						playerSlots[3].player.cards.push(c);

						var h = new Card(CardSuit.Hidden, CardValue.Hidden);
						h.tx = 800;
						h.ty = 0;
						displayedCards.push(h);
					}
					else if(stateIndex_DealPreFlop == 8)
					{
						var c = gameDeck.cards.pop();
						playerSlots[3].player.cards.push(c);

						var h = new Card(CardSuit.Hidden, CardValue.Hidden);
						h.tx = 850;
						h.ty = 0;
						displayedCards.push(h);
					}

					stateIndex_DealPreFlop++;

					if(8 < stateIndex_DealPreFlop)
					{
						playerAskBet = playingPlayers[(dealerIndex + 3) % playingPlayers.length];
						playerHighestBetting = playerAskBet;
						stateNext.push(GameState.DealFlop);
						//stateNext.push(GameState.AskForBets);
						stateCurrent = GameState.AskForBets;
					}
					else
					{
						//stateNext.push(GameState.DealPreFlop);
						stateCurrent = GameState.DealPreFlop;
					}

					//stateCurrent = GameState.WaitForCards;
                }
                function DealFlop()
                {
					console.debug("game: state: DealFlop");

					{
						var c = gameDeck.cards.pop();
						displayedCards.push(c);
						c.tx = 200;
						c.ty = 300;
						communityCards.push(c);
					}

					{
						var c = gameDeck.cards.pop();
						displayedCards.push(c);
						c.tx = 300;
						c.ty = 300;
						communityCards.push(c);
					}

					{
						var c = gameDeck.cards.pop();
						displayedCards.push(c);
						c.tx = 400;
						c.ty = 300;
						communityCards.push(c);
					}

					playerAskBet = playingPlayers[(dealerIndex + 3) % playingPlayers.length];
					playerHighestBetting = playerAskBet;
					stateNext.push(GameState.DealTurn);
					stateNext.push(GameState.AskForBets);
					stateCurrent = GameState.WaitForCards;
                }
				function DealTurn()
				{
					console.debug("game: state: DealTurn");

					{
						var c = gameDeck.cards.pop();
						displayedCards.push(c);
						c.tx = 500;
						c.ty = 300;
						communityCards.push(c);
					}

					playerAskBet = playingPlayers[(dealerIndex + 3) % playingPlayers.length];
					playerHighestBetting = playerAskBet;
					stateNext.push(GameState.DealRiver);
					stateNext.push(GameState.AskForBets);
					stateCurrent = GameState.WaitForCards;
				}
				function DealRiver()
				{
					console.debug("game: state: DealRiver");

					{
						var c = gameDeck.cards.pop();
						displayedCards.push(c);
						c.tx = 600;
						c.ty = 300;
						communityCards.push(c);
					}

					playerAskBet = playingPlayers[(dealerIndex + 3) % playingPlayers.length];
					playerHighestBetting = playerAskBet;
					stateNext.push(GameState.FinalState);
					stateNext.push(GameState.AskForBets);
					stateCurrent = GameState.WaitForCards;
				}
				function FinalState()
				{
					/*
					var Combos = {
						"Royal Straight Flush": 7,
						"Straight Flush": 7,
						"Four of a Kind": 6,
						"Full House": 5,
						"Flush": 4,
						"Straight": 3,
						"Three of a Kind": 2,
						"Two Pair": 1,
						"High Card": 0
					}

					var c2e = new Array();

					for(var ia=0; ia<communityCards.length; ia++)
					{
						c2e.push(communityCards[ia]);
					}

					for(var ia=0; ia<playingPlayers[0].cards.length; ia++)
					{
						c2e.push(playingPlayers[0].cards[ia]);
					}

					c2e.sort(function(a, b){
						return a.value < b.value;
					});

					console.log(c2e);
					*/
				}
                function Update()
                {
                    //console.debug("Update");

					//all the diffrent non-blocking main states the game can have
					if(stateCurrent == GameState.WaitForCards)
						WaitForCards();
					else if(stateCurrent == GameState.WaitForPlayers)
						WaitForPlayers();
					else if(stateCurrent == GameState.AskForBlinds)
						AskForBlinds();
					else if(stateCurrent == GameState.AskForBets)
						AskForBets();
					else if(stateCurrent == GameState.CheckBets)
						CheckBets();
					else if(stateCurrent == GameState.PotEatBets)
						PotEatBets();
                    else if(stateCurrent == GameState.DealPreFlop)
						DealPreFlop();
					else if(stateCurrent == GameState.DealFlop)
						DealFlop();
					else if(stateCurrent == GameState.DealTurn)
						DealTurn();
					else if(stateCurrent == GameState.DealRiver)
						DealRiver();
					else if(stateCurrent == GameState.FinalState)
						FinalState();

					//animate currently displayed cards
					for(var ia=0; ia<displayedCards.length; ia++)
						displayedCards[ia].Update();
                }
                function Draw(g)
                {
                    //console.log("Draw");
                    //g.save();
                    //g.setTransform(1, 0, 0, 1, 0, 0);
                    g.clearRect(0, 0, myCanvas.width, myCanvas.height);
                    //g.restore();

                    for(var ia=0; ia<playerSlots.length; ia++)
                    {
                        playerSlots[ia].Draw(g);
                    }

					for(var ia=0; ia<displayedCards.length; ia++)
                    {
						displayedCards[ia].Draw(g);
					}

					potMoney.Draw(g);
                }
                function Reset()
                {
                    gameDeck = new Deck();
                    gameDeck.Reset();
					gameDeck.Shuffle();
                    //gameDeck.AlignAll();

					potMoney = new ChipMoney(450, 450);

                    playerSlots = new Array();
                    playerSlots.push( new PlayerSlot(0.0, 0.0) );
                    playerSlots.push( new PlayerSlot(250.0, 0.0) );
                    playerSlots.push( new PlayerSlot(500.0, 0.0) );
                    playerSlots.push( new PlayerSlot(750.0, 0.0) );
                    playerSlots.push( new PlayerSlot(0.0, 600.0) );
                    playerSlots.push( new PlayerSlot(250.0, 600.0) );
                    playerSlots.push( new PlayerSlot(500.0, 600.0) );
                    playerSlots.push( new PlayerSlot(750.0, 600.0) );

					displayedCards = new Array();
					communityCards = new Array();
					playingPlayers = new Array();

					{
						var p = new HumanPlayer();
						p.money = 1000.0;
						playerSlots[0].player = p;
						playerSlots[0].isOccupied = true;
						playingPlayers.push(p);
						p.cards = new Array();
						p.isReady = true;
					}

					{
						var p = new ComputerPlayer();
						p.money = 1000.0;
						playerSlots[1].player = p;
						playerSlots[1].isOccupied = true;
						playingPlayers.push(p);
						p.cards = new Array();
						p.isReady = true;
					}

					{
						var p = new ComputerPlayer();
						p.money = 1000.0;
						playerSlots[2].player = p;
						playerSlots[2].isOccupied = true;
						playingPlayers.push(p);
						p.cards = new Array();
						p.isReady = true;
					}

					{
						var p = new ComputerPlayer();
						p.money = 1000.0;
						playerSlots[3].player = p;
						playerSlots[3].isOccupied = true;
						playingPlayers.push(p);
						p.cards = new Array();
						p.isReady = true;
					}

					dealerIndex = 0;
					stateIndex_DealPreFlop = 0;

					stateNext = new Array();
					stateNext.push(GameState.AskForBlinds);
					stateCurrent = GameState.WaitForPlayers;
                }
                return{
                    "Update": Update,
                    "Draw": Draw,
                    "Reset": Reset
                }
            }

			//the starting stateCurrent of the page
            var myGame = new Game();
            var myLoop = null;

            function ResumeGame()
            {
                console.debug("game resume");
                myLoop = setInterval( function(){
                    try
                    {
                        myGame.Update();
                        myGame.Draw(myContext);
                    }
                    catch(er)
                    {
                        throw er;
                    }
                    finally
                    {
                        //PauseGame();
                    }
                }, 1000.0/60.0);
            }

            function PauseGame()
            {
				if(myLoop == null)
					return; //then ignore

                console.debug("game pause");
                clearInterval(myLoop);
            }

            function ResetGame()
            {
                console.debug("game reset");
                myGame.Reset();
            }

            ResetGame();
            ResumeGame();

            //buttons add event handlers
            buttonReset.onclick = ResetGame;
            buttonResume.onclick = ResumeGame;
            buttonPause.onclick = PauseGame;

			var Combos = {
				"Royal Straight Flush": 7,
				"Straight Flush": 7,
				"Four of a Kind": 6,
				"Full House": 5,
				"Flush": 4,
				"Straight": 3,
				"Three of a Kind": 2,
				"Two Pair": 1,
				"High Card": 0
			}

			var c2e = new Array(); //cards to evaluate

			c2e.push(new Card(CardSuit.Space, CardValue.Ace));
			c2e.push(new Card(CardSuit.Space, CardValue.King));

			c2e.push(new Card(CardSuit.Space, CardValue.Jack));
			c2e.push(new Card(CardSuit.Space, CardValue.Ten));
			c2e.push(new Card(CardSuit.Space, CardValue.Nine));
			c2e.push(new Card(CardSuit.Space, CardValue.Eight));

			c2e.push(new Card(CardSuit.Space, CardValue.Queen));

			c2e.sort(function(a, b){
				return a.value < b.value;
			});

			console.log(c2e);

			var flushCount = 0;
			var pairCount = 0;
			var straightCount = 0;

			for(var ia=0; ia<c2e.length; ia++)
			{
				if(straight.length == 0 || straight)
					;
			}

			console.log("straightCount: " + straightCount);
        </script>
    </body>
</html>
