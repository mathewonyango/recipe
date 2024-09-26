// public/js/custom.js

$(document).ready(function() {
    $('#casesTable').DataTable();

    $('#selectAll').click(function() {
        var rows = $('#casesTable').DataTable().rows({ 'search': 'applied' }).nodes();
        $('input[type="checkbox"]', rows).prop('checked', this.checked);
    });

    $('#casesTable tbody').on('change', 'input[type="checkbox"]', function() {
        if (!this.checked) {
            var el = $('#selectAll').get(0);
            if (el && el.checked && ('indeterminate' in el)) {
                el.indeterminate = true;
            }
        }
    });

    // Show shuffle agents modal on button click
    $('#shuffleAgentsButton').click(function() {
        $('#shuffleAgentsModal').modal('show');
    });

    // Show bulk allocation modal on button click
    $('#bulkAllocationButton').click(function() {
        $('#bulkAllocationModal').modal('show');
    });

    // Add agent shuffle entry
    $('#addAgentShuffle').click(function() {
        var currentAgent = $('#currentAgent').val();
        var newAgent = $('#newAgent').val();
        if (currentAgent && newAgent) {
            var row = `<tr>
                <td>${currentAgent}</td>
                <td>${newAgent}</td>
                <td><button class="btn btn-danger btn-sm remove-row">Remove</button></td>
            </tr>`;
            $('#agentShuffleList').append(row);
        }
    });

    // Remove agent shuffle entry
    $('#agentShuffleList').on('click', '.remove-row', function() {
        $(this).closest('tr').remove();
    });
});
