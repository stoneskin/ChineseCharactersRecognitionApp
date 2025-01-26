<!-- Activity Details Modal -->
<div class="modal fade" id="activityDetailsModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title">Activity Details</h3>
                <button type="button" class="close" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div id="wordList" class="row">
                    <!-- Word blocks will be inserted here -->
                </div>
            </div>
        </div>
    </div>
</div>

<script>
$(document).ready(function() {


    // Handle view details
    $('.view-details').click(function() {
        const activityId = $(this).data('activity-id');
        loadWordDetails(activityId);
        // $.ajax({
        //     url: 'api/getWordDetails.php',
        //     method: 'GET',
        //     data: { activityId: activityId },
        //     success: function(response) {
        //         $('#activityDetailsContent').html(response);
        //         $('#activityDetailsModal').modal('show');
        //     },
        //     error: function() {
        //         alert('Error loading activity details');
        //     }
        // });
    });
    function loadWordDetails(activityId) {
        $.ajax({
            url: 'api/getWordDetails.php',
            method: 'GET',
            data: { activityId: activityId },
            success: function(response) {
                const words = JSON.parse(response);
                displayWords(words);
                $('#activityDetailsModal').modal('show');
            },
            error: function(xhr, status, error) {
                alert('Error loading word details: ' + error);
            }
        });
    }
    function displayWords(words) {
        const wordList = $('#wordList');
        wordList.empty();
        
        words.forEach(word => {
            const wordBlock = $(`
                <div class="col-md-4 col-sm-6 mb-3 word-block" data-passed="${word.Passed}">
                    <div class="card ${word.Passed == 1 ? 'border-success' : 'border-danger'}">
                        <div class="card-body">
                            <h4 class="card-title">${word.Words}</h4>
                            <p class="card-text">
                                Time: ${word.TimeElapsed} seconds<br>
                            
                            </p>
                        </div>
                    </div>
                </div>
            `);
            wordList.append(wordBlock);
        });
    }

});


</script>