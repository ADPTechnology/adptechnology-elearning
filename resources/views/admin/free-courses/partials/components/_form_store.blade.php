<div class="modal-body">

    <table id="fc-course-users-participants-table" class="table table-hover"
        data-url="{{ route('admin.subscriptions.getUsersList') }}">
        <thead>
            <tr>
                <th>Elegir</th>
                <th>N°</th>
                <th>Email</th>
                <th>Nombre</th>
            </tr>
        </thead>
    </table>

    <div class="form-group mt-3" id="container-select-plan">
        @include('admin.free-courses.partials.components._plans_select')
    </div>

</div>
