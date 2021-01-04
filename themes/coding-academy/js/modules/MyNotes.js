import $ from "jquery"

class MyNotes {
    constructor() {
        this.events()
    }

    events() {
        //$(".edit-note").on("click", this.editNote.bind(this))
        $("#my-notes").on("click", ".edit-note", this.editNote.bind(this)) // Binding events for future Edit buttons generated after page loading (under '#my-notes' ul)
        $("#my-notes").on("click", ".update-note", this.updateNote.bind(this))
        $("#my-notes").on("click", ".delete-note", this.deleteNote)
        $(".submit-note").on("click", this.createNote.bind(this))
    }

    // Methods
    editNote(e) {
        var thisNote = $(e.target).parents("li")
        if (thisNote.data("state") == "editable") {
            this.makeNoteReadOnly(thisNote)
        } else {
            this.makeNoteEditable(thisNote)
        }
    }

    makeNoteEditable(thisNote) {
        thisNote.find(".edit-note").html('<i class="fa fa-times" aria-hidden="true"> Cancel</i>')
        thisNote.find(".note-title-field, .note-body-field").removeAttr("readonly").addClass("note-active-field")
        thisNote.find(".update-note").addClass("update-note--visible")
        thisNote.data("state", "editable")
    }

    makeNoteReadOnly(thisNote) {
        thisNote.find(".edit-note").html('<i class="fa fa-pencil" aria-hidden="true"> Edit</i>')
        thisNote.find(".note-title-field, .note-body-field").attr("readonly", "readonly").removeClass("note-active-field")
        thisNote.find(".update-note").removeClass("update-note--visible")
        thisNote.data("state", "cancel")
    }

    updateNote(e) {
        let thisNote = $(e.target).parents("li")
        let updatedNote = {
            'title': thisNote.find(".note-title-field").val(),
            'content': thisNote.find(".note-body-field").val()
        }
        $.ajax({
            beforeSend: (xhr) => {
                xhr.setRequestHeader('X-WP-Nonce', academyData.nonce)
            },
            url: academyData.root_url + "/wp-json/wp/v2/note/" + thisNote.data("id"),
            type: 'POST',
            data: updatedNote,
            success: (response) => {
                this.makeNoteReadOnly(thisNote)                
                //console.log(response);
            },
            error: (response) => {
                console.log("error");
                console.log(response);           
            }
        })
    }

    deleteNote(e) {
        let thisNote = $(e.target).parents("li")
        $.ajax({
            beforeSend: (xhr) => {
                xhr.setRequestHeader('X-WP-Nonce', academyData.nonce)
            },
            url: academyData.root_url + "/wp-json/wp/v2/note/" + thisNote.data("id"),
            type: 'DELETE',
            success: (response) => {                
                thisNote.slideUp(); // Remove it from the page                
                //console.log(response);
                if (response.userNoteCount < 5) {
                    $(".note-limit-message").removeClass("active")
                }
            },
            error: (response) => {
                console.log("error");
                console.log(response);
            }
        });
    }

    createNote(e) { 
        let newNote = {
            'title': $(".new-note-title").val(),
            'content': $(".new-note-body").val(),
            'status': 'publish' // We make this note 'Private' from the server side            
        }
        $.ajax({
            beforeSend: (xhr) => {
                xhr.setRequestHeader('X-WP-Nonce', academyData.nonce)
            },
            // If no ID attached to the URL, it will create a new one with the POST method
            url: academyData.root_url + "/wp-json/wp/v2/note/", 
            type: 'POST',
            data: newNote,
            success: (response) => {
                $(".new-note-title, .new-note-body").val('')
                // '#my-notes': UL id, hide().scrollDown(): for gradual appearance
                $(`
                <li data-id="${response.id}">
                    <input readonly class="note-title-field" value="${response.title.raw}">
                    <span class="edit-note"><i class="fa fa-pencil" aria-hidden="true"> Edit</i></span>
                    <span class="delete-note"><i class="fa fa-trash-o" aria-hidden="true"> Delete</i></span>
                    <textarea readonly class="note-body-field">${response.content.raw}</textarea>
                    <span class="update-note btn btn--blue btn--small"><i class="fa fa-arrow-right" aria-hidden='true'> Save</i></span>                   
                </li>                
                `).prependTo("#my-notes").hide().slideDown()                
                //console.log(response);
            },
            error: (response) => {
                if (response.responseText == "You have reached your note limit.") {
                    $(".note-limit-message").addClass("active")
                }
                console.log("error");
                console.log(response);           
            }
        })        
    }

}

export default MyNotes;