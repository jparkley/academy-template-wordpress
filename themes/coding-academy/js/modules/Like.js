import $ from 'jquery'

class Like {
    constructor() {
        this.events()
    }

    events() {
        $(".like-box").on("click", this.clickDispatcher.bind(this))
    }

    // Methods
    clickDispatcher(e) {
        let currentLikeBox = $(e.target).closest(".like-box");

        if (currentLikeBox.attr("data-exists") == 'yes') { // 'attr' to include fresh updated values
            this.deleteLike(currentLikeBox)
        } else {
            this.createLike(currentLikeBox)
        }
    }

    createLike(currentLikeBox) {
        let professor = currentLikeBox.data('professor')
        $.ajax({
            beforeSend: (xhr) => {
                xhr.setRequestHeader('X-WP-Nonce', academyData.nonce)
            },
            url: academyData.root_url + '/wp-json/academy/v1/manageLike',
            type: 'POST',
            data: {'professor': professor},
            success: (response) => {
                currentLikeBox.attr("data-exists", "yes")             
                let likeCount = parseInt(currentLikeBox.find(".like-count").html(), 10)
                likeCount++
                currentLikeBox.find(".like-count").html(likeCount)
                currentLikeBox.attr("data-likeid", response); // In success, it returns the new ID
                //console.log(response);
            },
            error: (response) => {
                console.log(response);
            }
        })
    }

    deleteLike(currentLikeBox) {
        $.ajax({
            beforeSend: (xhr) => {
                xhr.setRequestHeader('X-WP-Nonce', academyData.nonce)
            },            
            url: academyData.root_url + '/wp-json/academy/v1/manageLike',
            data: {'like': currentLikeBox.attr('data-likeid')},
            type: 'DELETE',
            success: (response) => {
                currentLikeBox.attr("data-exists", "no")
                let likeCount = parseInt(currentLikeBox.find(".like-count").html(), 10)
                likeCount--
                currentLikeBox.find(".like-count").html(likeCount)                
                currentLikeBox.attr("data-likeid", ''); 
                console.log(response);
            },
            error: (response) => {
                console.log(response);
            }
        })        
    }
}
export default Like;