/* GLOBAL variables */
var emailReg = /^\s*[\w\-\+_]+(\.[\w\-\+_]+)*\@[\w\-\+_]+\.[\w\-\+_]+(\.[\w\-\+_]+)*\s*$/;

/* Check if password meets requirements */
function passvalid(pass)
{
	var regnum = /.*\d/; //number
	var reglet = /.*[a-z]/; //letters
	var regcaps = /.*[A-Z]/; //caps
	var regpunc = /.*[@#$%!^&*()\_\-\+=~<>,.?\/:;'"{}|`[\]]/; //symbols
	var regexp = [regnum, reglet, regcaps, regpunc];
	var sets = 0;

	var len = $("#"+pass).val().length;

	if (len == 0)
		return 2;
	else if(len < 6 || len > 255)
		return 3;
	else
	{
		for(var i=0 ; i < 4; i++)
		{
			if (regexp[i].test($("#"+pass).val()))
				sets++;
		}
		if (sets < 2)
			return 4; //less than 2 charsets in pass
		else
			return 1; //valid pass
	}
};

/* Boolean wrapper for passvalid() function */
function isvalidpass(pass)
{
	var valid = passvalid(pass);
	if (valid != 1)
		valid = false;
	else
		valid = true;
		
	return valid;
};
