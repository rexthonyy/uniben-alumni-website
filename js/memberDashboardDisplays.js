class Display {
    constructor(tabIndex){
        this.tabIndex = tabIndex;
    }

    static OpenMainView(){
		getMainContainer().style.display = "block";
		getMainProgressBar().style.display = "none";
	}

	static CloseMainView(){
		getMainContainer().style.display = "none";
		getMainProgressBar().style.display = "block";
    }
    
    static OpenDisplayView(){
        getDisplayContainer().style.display = "block";
		getDisplayProgressBar().style.display = "none";
    }

    static CloseDisplayView(){
        getDisplayContainer().style.display = "none";
		getDisplayProgressBar().style.display = "span";
    }

    static CloseAllDisplayTabViews(){
        let displayTabs = getDisplayTabs();
        for(let i = 0; i < displayTabs.length; i++){
            displayTabs[i].style.display = "none";
        }
    }

    openDisplayTabView(){
        getDisplayTabs()[this.tabIndex].style.display = "block";
    }
    
    closeDisplayTabView(){
        getDisplayTabs()[this.tabIndex].style.display = "none";
    }

    openView(){
        getViews()[this.tabIndex].style.display = "block";
    }
    
    closeView(){
        getViews()[this.tabIndex].style.display = "none";
    }

    setup(){
        Display.OpenDisplayView();
        this.openDisplayTabView();
        Display.OpenMainView();
    }

    close(){
        Display.CloseAllDisplayTabViews();
        Display.CloseDisplayView();
    }
}

class MyProfileDisplay extends Display {
	constructor(tabIndex){
		super(tabIndex);
	}
}

class PaymentHistoryDisplay extends Display {
    constructor(tabIndex){
        super(tabIndex);
    }
}

class ManageAccountDisplay extends Display {
    constructor(tabIndex){
        super(tabIndex);
    }
}

class AdminDisplay extends Display {
    constructor(tabIndex){
        super(tabIndex);
    }
}

class LogoutDisplay extends Display {
    constructor(tabIndex){
        super(tabIndex);
    }
}

class ViewsDisplay extends Display {
    constructor(tabIndex){
        super(tabIndex);
    }

    setup(){
        this.openView();
    }

    close(){
        this.closeView();
    }
}

class AdminViewDisplay extends ViewsDisplay {
    constructor(tabIndex){
        super(tabIndex);
    }
}

class SearchMembersViewDisplay extends ViewsDisplay {
    constructor(tabIndex){
        super(tabIndex);
    }
}

class MemberProfileViewDisplay extends ViewsDisplay {
    constructor(tabIndex){
        super(tabIndex);
    }
}

class EditPageViewDisplay extends ViewsDisplay {
    constructor(tabIndex){
        super(tabIndex);
    }
}

class NationalExcosViewDisplay extends ViewsDisplay {
    constructor(tabIndex){
        super(tabIndex);
    }
}

