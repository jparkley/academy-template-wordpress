import $ from 'jquery';

class Search {
    constructor() {
        this.openButton = $(".js-search-trigger");
        this.closeButton = $(".search-overlay__close");
        this.searchOverlay = $(".search-overlay");
        this.isOverlayOpen = false;
        this.searchField = $("#search-term");
        this.resultsDiv = $("#search-overlay__results");
        this.isSpinnerVisible = false;
        this.typingTimer;
        this.previousValue;
        this.events();
    }

    // Events
    events() {
        this.openButton.on("click", this.openOverlay.bind(this));
        this.closeButton.on("click", this.closeOverlay.bind(this));
        $(document).on("keydown", this.keyPressDispatcher.bind(this));        
        // 'keyup': browser will have enough time to update search field value
        this.searchField.on("keyup", this.typingLogic.bind(this));   
    }
    
    // Methods
    typingLogic() {
        if (this.searchField.val() != this.previousValue) {
            clearTimeout(this.typingTimer);
            if (this.searchField.val()) {
                if (!this.isSpinnerVisible) {
                    this.resultsDiv.html('<div class="spinner-loader"></div>');
                    this.isSpinnerVisible = true;                
                }  
                this.typingTimer = setTimeout(this.getResults.bind(this), 2000);              
            } else {
                this.resultsDiv.html('');
            }        
        }
        this.previousValue = this.searchField.val();        
    }

    getResults() {
        this.resultsDiv.html("right here show results");
        this.isSpinnerVisible = false;
    }

    keyPressDispatcher(e) {
        if (e.keyCode == 83 && !this.isOverlayOpen && !$("input, textarea").is(':focus')) { 
            // When the key 's' is pressed, open the overlay search
            // Also, there isn't another input or textarea focused on waiting for user input
            this.openOverlay();        
        }
        if (e.keyCode == 27 && this.isOverlayOpen) { // When the key 'esc' is pressed, close the overlay search
            this.closeOverlay();     
        }     

    }

    openOverlay() {
        this.searchOverlay.addClass("search-overlay--active");       
        $("body").addClass('body-no-scroll');
        this.isOverlayOpen = true;
    }

    closeOverlay() {
        this.searchOverlay.removeClass("search-overlay--active");
        $("body").removeClass('body-no-scroll')
        this.isOverlayOpen = false;
    }
}

export default Search;