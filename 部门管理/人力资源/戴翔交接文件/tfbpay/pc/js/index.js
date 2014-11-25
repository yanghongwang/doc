function ActiveIcon(icon)
{
	icon.className = icon.className + " active";
}
function NormalIcon(icon)
{
	var className = icon.className;
	icon.className = className.substring(0, className.indexOf(" active"));
}