@extends('layouts.main')
@section('body')
    <div class="w-100 d-flex">
        <div class="table-responsive my-5 mx-auto w-75">
            <table class="table table-bordered table-hover m-3">
                <thead>
                    <th>ID</th>
                    <th>Codigo</th>
                    <th>Monto</th>
                    <th>Fecha</th>
                    <th>Acciones</th>
                </thead>
                <tbody>
                    @foreach($users as $u)
                        <tr>
                            <td>{{$u['id']}}</td>
                            <td>{{$u['code']}}</td>
                            <td>${{$u['amount_formatted']}}</td>
                            <td>{{$u['date_formatted']}}</td>
                            <td>
                                <button type="button" onclick="editUser({{$u['id']}})" class="btn btn-primary">Editar</button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
    
        </div>
    </div>

    <div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title" id="exampleModalLabel">Editar usuario</h5>
              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
              
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-primary" onclick="actualizar()">Actualizar usuario</button>
            </div>
          </div>
        </div>
    </div>
@endsection

@section('script')
    <script>
        var editModalEl = document.getElementById('editModal');
        var editModal = new bootstrap.Modal(editModalEl, {})

        function editUser(id) {
            fetch('/getUser/' + id, {method:'GET'})
            .then(response => {
                if(response.headers.get('content-type').includes('application/json'))
                    return response.json();
                else
                    return response.text();
            })
            .then((data) => {
                if(typeof data === 'object') {
                    console.log(data);
                    alert(data.mensaje);
                } else {
                    editModalEl.querySelector(".modal-body").innerHTML = data;
                    editModal.show();
                }
            });
        }

        function actualizar() {
            const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

            var formData = new FormData(document.getElementById('form'));
            fetch('/updateUser/', {method:'POST',body:formData,headers: {
                'X-CSRF-TOKEN': csrfToken
            },})
            .then(response => {
                return response.json();
            })
            .then((data) => {
                alert(data.mensaje);
                editModal.hide();
            });
        }
    </script>
@endsection