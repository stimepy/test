// JavaScript Document
var selectedList;
var availableList;
function createListObjects(avail, selected){
    availableList = document.getElementById(avail)
    selectedList = document.getElementById(selected);
	selectAll(selectedList);
}
function delAttribute(){
   var selIndex = selectedList.selectedIndex;
   if(selIndex < 0)
      return;
   availableList.appendChild(
      selectedList.options.item(selIndex))
   selectNone(selectedList,availableList);
}
function addAttribute(){
   var addIndex = availableList.selectedIndex;
   if(addIndex < 0)
      return;
   selectedList.appendChild( 
      availableList.options.item(addIndex));
   selectNone(selectedList,availableList);}
function selectNone(list1,list2){
    list1.selectedIndex = -1;
    list2.selectedIndex = -1;
    addIndex = -1;
    selIndex = -1;
	selectAll(selectedList);
}
function selectAll(CONTROL){
	for(var i = 0;i < CONTROL.length;i++){
		CONTROL.options[i].selected = true;
	}
}