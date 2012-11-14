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
                width: 960px;
                height: 720px;
                margin: 0px auto;
                border: 1px solid red;
            }
        </style>
    </head>
    <body>
        <div id="test">
            <canvas id="myCanvas" width=960 height=720></canvas>
        </div>
        <script>
            var canvas = document.getElementById('myCanvas');
            var context = canvas.getContext('2d');
            var gc = context;
            var imageObj = new Image();
            imageObj.onload = function()
            {
                context.drawImage(imageObj, 600, 600);
            };
            imageObj.src = 'http://www.html5canvastutorials.com/demos/assets/darth-vader.jpg';

            function Foo()
            {
                this.name = "foo";
            }
            Foo.prototype.sayHello = function()
            {
                console.log("hello from " + this.name);
            };
            var myFoo = new Foo();
            myFoo.sayHello();
            
            //chrome Version 23.0.1271.64 m 2012-11-14

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
                Two: "2",
                Three: "3",
                Four: "4",
                Five: "5",
                Six: "6",
                Seven: "7",
                Eight: "8",
                Nine: "9",
                Ten: "T",
                Jack: "J",
                Queen: "Q",
                King: "K",
                Ace: "A",
                Hidden: "?"
            }
            
            //card class
            function Card(s, v)
            {
                this.suit = s;
                this.value = v;
                this.x = 0;
                this.y = 0;
                this.w = 75;
                this.h = 100;
            }
            Card.prototype.toString = function()
            {
                return this.suit + ":" + this.value;
            };
            Card.prototype.Draw = function(g)
            {
                //draw card background
                g.beginPath();
                g.rect(this.x, this.y, this.w, this.h);
                g.fillStyle = 'white';
                g.fill();
                g.lineWidth = 1;
                g.strokeStyle = 'black';
                g.stroke();
                                
                //draw card content
                g.textBaseline = "top";
                g.font = "32pt Arial";
                
                if(this.suit == CardSuit.Spade)
                {
                    g.strokeStyle = "black";
                    g.strokeText(this.suit, this.x + 26, this.y + 24);
                }
                else if(this.suit == CardSuit.Heart)
                {
                    g.strokeStyle = "red";
                    g.strokeText(this.suit, this.x + 24, this.y + 24);
                }
                else if(this.suit == CardSuit.Club)
                {
                    g.strokeStyle = "black";
                    g.strokeText(this.suit, this.x + 24, this.y + 24);
                }
                else if(this.suit == CardSuit.Diamond)
                {
                    g.strokeStyle = "red";
                    g.strokeText(this.suit, this.x + 27, this.y + 23);
                }
                else if(this.suit == CardSuit.Hidden)
                {
                    g.strokeStyle = "black";
                    g.strokeText(this.suit, this.x + 25, this.y + 24);
                }
                else
                {
                    throw "Card: error invalid suit value";
                }
                
                g.font = "10pt Arial";                    
                g.strokeText(this.value, this.x + 5, this.y + 5);

                g.rotate(Math.PI);                    
                g.strokeText(this.value, -this.x - 68, -this.y - 95);
                g.rotate(Math.PI);
            }
                        
            //deck class
            function Deck()
            {
                this.cards = null;
            }
            Deck.prototype.Reset = function()
            {
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
                this.cards.push( new Card(CardSuit.Hidden, CardValue.Hidden ) );
                this.cards.push( new Card(CardSuit.Hidden, CardValue.Hidden ) );
                this.cards.push( new Card(CardSuit.Hidden, CardValue.Hidden ) );
            }
            Deck.prototype.toString = function()
            {
                return "Deck???";
            }
                        
            var d = new Deck();
            d.Reset();
            //console.log(d.cards);
            console.log(d.toString());
            
            var offX = 0;
            var offY = 0;
            
            for(var ia=0; ia<d.cards.length; ia++)
            {
                d.cards[ia].x = offX;
                d.cards[ia].y = offY;                
                d.cards[ia].Draw(context);
                
                offX += d.cards[ia].w;
                
                if(ia % 12 == 11)
                {
                    offY += d.cards[ia].h;
                    offX = 0;
                }
            }
            
        </script>
    </body>
</html>
