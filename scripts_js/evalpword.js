$(document).ready(function() 
{
	var strPassword;
	var charPassword;
	var complexity = $("#complexity");
	var minPasswordLength = 8;
	var baseScore = 0, score = 0, rpcount = 0;
	
	var num = {};
	num.Excess = 0;
	num.Upper = 0;
	num.Numbers = 0;
	num.Symbols = 0;
	num.Repeats = 0;

	var bonus = {};
	bonus.Excess = 3;
	bonus.Upper = 4;
	bonus.Numbers = 5;
	bonus.Symbols = 5;
	bonus.Combo = 0; 
	bonus.FlatLower = 0;
	bonus.FlatNumber = 0;
	bonus.FlatUpper = 0;
	bonus.MedMatch = 0;
	bonus.StrongMatch = 0;
	
	outputResult();
	$("#inputPassword").bind("keyup", checkVal);

function checkVal()
{
	init();
	
	if (charPassword.length >= minPasswordLength)
	{
		baseScore = 50;	
		analyzeString();	
		calcComplexity();		
	}
	else
	{
		baseScore = 0;
	}
	
	outputResult();
}

function init()
{
	strPassword= $("#inputPassword").val();
	charPassword = strPassword.split("");
		
	num.Excess = 0;
	num.Upper = 0;
	num.Numbers = 0;
	num.Symbols = 0;
	num.Repeats = 0;
	bonus.Combo = 0; 
	bonus.FlatLower = 0;
	bonus.FlatNumber = 0;
	bonus.FlatUpper = 0;
   bonus.MedMatch = 0;
	bonus.MedStrong = 0;
	baseScore = 0;
	score =0;
}

function analyzeString ()
{
   
	for (i=0; i<charPassword.length; i++)
	{
		if (charPassword[i].match(/[A-Z]/g)) {num.Upper++;}
		if (charPassword[i].match(/[0-9]/g)) {num.Numbers++;}
		if (charPassword[i].match(/(.*[!,@,#,$,%,^,&,*,?,_,~])/)) {num.Symbols++;}
		rpcount = 0;
		for (j=0; j<strPassword.length; j++)  {
		   if (charPassword[i] == strPassword[j]) {
		   rpcount++; }
		}
		num.Repeats = num.Repeats + rpcount - 1;
	}

	if (num.Repeats == strPassword.length) {
	   baseScore = baseScore - (num.Repeats * num.Repeats);
	   }
	num.Excess = charPassword.length - minPasswordLength;
	
	if (num.Upper && num.Numbers && num.Symbols)
	{
		bonus.Combo = 25; 
	}

	else if ((num.Upper && num.Numbers) || (num.Upper && num.Symbols) || (num.Numbers && num.Symbols))
	{
		bonus.Combo = 15; 
	}
	
	if (strPassword.match(/^[\sa-z]+$/))
	{ 
		bonus.FlatLower = -15;
	}
	
	if (strPassword.match(/^[\sA-Z]+$/))
	{ 
		bonus.FlatUpper = -15;
	}
	
	if (strPassword.match(/^[\s0-9]+$/))
	{ 
		bonus.FlatNumber = -35;
	}
	if (strPassword.match(/^(?=.{7,})(((?=.*[A-Z])(?=.*[a-z]))|((?=.*[A-Z])(?=.*[0-9]))|((?=.*[a-z])(?=.*[0-9]))).*$/g))
	{  bonus.MedMatch = 15;
	}
	if (strPassword.match(/^(?=.{8,})(?=.*[A-Z])(?=.*[a-z])(?=.*[0-9])(?=.*\W).*$/g))
	{  bonus.MedStrong = 30;
	}
	if ((bonus.FlatLower < 0) || (bonus.FlatNumber < 0) || (bonus.FlatUpper < 0))
	 {num.Excess = 0;
	 }
}
	
function calcComplexity()
{
	score = baseScore + (num.Excess*bonus.Excess) + (num.Upper*bonus.Upper) + (num.Numbers*bonus.Numbers) + (num.Symbols*bonus.Symbols) + bonus.Combo + bonus.FlatLower + bonus.FlatNumber + bonus.MedMatch + bonus.MedStrong + bonus.FlatUpper - num.Repeats;

}	
	
function outputResult()
{
	if ($("#inputPassword").val()== "")
	{ 
		complexity.html("Minimum of 8 characters").removeClass("weak strong stronger strongest").addClass("default");
	}
	else if (charPassword.length < minPasswordLength)
	{
		complexity.html("At least " + minPasswordLength + " characters please!").removeClass("strong stronger strongest").addClass("weak");
	}
	else if (score<50)
	{
		complexity.html("Weak!").removeClass("strong stronger strongest").addClass("weak");
	}
	else if (score>=50 && score<75)
	{
		complexity.html("Average!").removeClass("stronger strongest").addClass("strong");
	}
	else if (score>=75 && score<100)
	{
		complexity.html("Strong!").removeClass("strongest").addClass("stronger");
	}
	else if (score>=100)
	{
		complexity.html("Strongest!").addClass("strongest");
	}

}

}
);