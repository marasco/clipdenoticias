function limpiar(ido)
{
	o = document.getElementById(ido);
	o.value = "";
	};
function popup(state)
{
	//alert("hey");
var obj = document.getElementById("access");
	
if (state==1){
//	alert("hey");
	obj.style.visibility="visible";
	//obj.style.position = "relative";
}
	else {
	obj.style.visibility="hidden";
}
};

function chgColor(o,color)
{
	var obj;
	obj = document.getElementById(o);
	if (obj!=null) { obj.style.background = color; }
	
};
function showObj(objId)
	{
	var obj = document.getElementById(objId);
		if (obj.style.visibility == "hidden")
		{
			obj.style.visibility = "visible";
			obj.style.height = "auto";
			obj.style.overflow = "auto";
		} else
		{
			obj.style.visibility = "hidden";
			obj.style.overflow = "hidden";
			obj.style.height = "1px";
		};
	};