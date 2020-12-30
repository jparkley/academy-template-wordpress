import $ from 'jquery';

class Search {
    constructor() {
        this.addSearchHTML();
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
                this.typingTimer = setTimeout(this.getResults.bind(this), 750);              
            } else {
                this.resultsDiv.html('');
            }        
        }
        this.previousValue = this.searchField.val();        
    }

    getResults() {
        // Async search
        $.when(
            $.getJSON(academyData.root_url + "/wp-json/wp/v2/posts?search=" + this.searchField.val()), 
            $.getJSON(academyData.root_url + "/wp-json/wp/v2/pages?search=" + this.searchField.val())
            ).then((posts, pages) => {
            let combindResults = posts[0].concat(pages[0]);
            this.resultsDiv.html(`
            <h2 class="search-overlay__section-title">Search Results</h2>
            ${combindResults.length ? '<ul class="link-list min-list">' : '<p>No matching results</p>'}           
                ${combindResults.map(item => `<li><a href="${item.link}">${item.title.rendered}</a></li>`).join('')}
            ${combindResults.length ? '</ul>' : ''}
            `);
            this.isSpinnerVisible = false;                
        }, () => {
            this.resultsDiv.html('<p>Unexpected error.  Please try again.</p>')
        });        
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
        // waiting is needed because it's goint to focus before the  overlay is loaded
        setTimeout(() => this.searchField.focus(), 301);        
        this.isOverlayOpen = true;
        this.searchField.val('');
        this.resultsDiv.html('');
    }

    closeOverlay() {
        this.searchOverlay.removeClass("search-overlay--active");
        $("body").removeClass('body-no-scroll')
        this.isOverlayOpen = false;
    }

    addSearchHTML() {
        $("body").append(`
        <div class="search-overlay">
        <div class="search-overlay__top">
          <div class="container">
            <i class="fa fa-search search-overlay__icon" aria-hidden="true"></i>
            <input type="text" class="search-term" id="search-term" placeholder="What are you looking for?">
            <i class="fa fa-window-close search-overlay__close" aria-hidden="true"></i>
          </div>
        </div>  
        <div class="container">
          <div id="search-overlay__results"></div>
        </div>
      </div>        
        `);
    }
}

export default Search;