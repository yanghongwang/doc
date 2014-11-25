if(!SServerSelect)
{
    var SServerSelect={};
}


SServerSelect.STD_DATA= 
[

    {t:"电信区", v:"214", opt_data_array:[

        {t: "电信体验区",v: "1",status:"1", opt_data_array:[]}

]}
,
    {t:"网通区", v:"314", opt_data_array:[

        {t: "网通体验区",v: "600",status:"1", opt_data_array:[]}

]}

];


SServerSelect.areaChange=function()
{
    SServerSelect.serverSelect.options.length = 0; 
    
    var selectIndex=SServerSelect.areaSelect.selectedIndex;
    for(var i=0;i<SServerSelect.STD_DATA[selectIndex].opt_data_array.length;i++)
    {
        var varItem = new Option(SServerSelect.STD_DATA[selectIndex].opt_data_array[i].t,SServerSelect.STD_DATA[selectIndex].opt_data_array[i].v); 
        SServerSelect.serverSelect.options.add(varItem);   
    }
  
}


SServerSelect.showzone=function(select_area, select_server)
{
    SServerSelect.areaSelect=select_area;
    SServerSelect.serverSelect=select_server;
    
    for(var i=0;i<SServerSelect.STD_DATA.length;i++)
    {
        var varItem = new Option(SServerSelect.STD_DATA[i].t,SServerSelect.STD_DATA[i].v); 
        SServerSelect.areaSelect.options.add(varItem);
    }
    SServerSelect.areaSelect.options[0].selected=true;
    SServerSelect.areaSelect.attachEvent('onchange',SServerSelect.areaChange); 

    SServerSelect.areaChange();   
    
}