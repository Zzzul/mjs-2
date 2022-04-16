@extends('layouts.master')
@section('title', 'Create New BAC terima')

@section('content')
    <div id="content" class="app-content">

        {{ Breadcrumbs::render('new_bac_terima_create') }}

        <form action="{{ route('new-bac-terima.store') }}" method="POST" id="form-bac" enctype="multipart/form-data">
            @include('inventory.new-bac-terima.include.cart')
        </form>
    </div>
@endsection

@push('js')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.all.min.js"></script>

    {{-- items --}}
    <script>
        const kode = $('#kode')
        const tanggal = $('#tanggal')

        const produk = $('#produk')
        const kodeProduk = $('#kode-produk')
        const unitProduk = $('#unit-produk')
        const qtyTerima = $('#qty-terima')
        const keterangan = $('#keterangan')
        const stok = $('#stok')

        const btnAdd = $('#btn-add')
        const btnUpdate = $('#btn-update')
        const btnSave = $('#btn-save')
        const btnCancel = $('#btn-cancel')

        const tblCart = $('#tbl-cart')

        getKode()

        tanggal.change(function() {
            kode.val('Loading...')

            getKode()
        })

        produk.change(function() {
            if (!kode.val()) {
                kode.focus()
                produk.val('')

                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Kode tidak boleh kosong'
                })

            } else if (!tanggal.val()) {
                tanggal.focus()
                produk.val('')

                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Tanggal tidak boleh kosong'
                })

            } else {
                qtyTerima.prop('type', 'text')
                qtyTerima.prop('disabled', true)
                qtyTerima.val('Loading...')

                $.ajax({
                    url: '/inventory/item/get-item-by-id/' + $(this).val(),
                    method: 'GET',
                    success: function(res) {
                        stok.val(res.stok)
                        kodeProduk.val(res.kode)
                        unitProduk.val(res.unit.nama)

                        setTimeout(() => {
                            qtyTerima.prop('type', 'number')
                            qtyTerima.prop('disabled', false)
                            qtyTerima.val('')
                            qtyTerima.focus()
                        }, 500)
                    }
                })
            }
        })

        btnAdd.click(function() {
            if (!kode.val()) {
                kode.focus()

                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Kode tidak boleh kosong'
                })

            } else if (!tanggal.val()) {
                tanggal.focus()

                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Tanggal tidak boleh kosong'
                })

            } else if (!keterangan.val()) {
                keterangan.focus()

                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Keterangan tidak boleh kosong'
                })

            } else if (!produk.val() || !qtyTerima.val()) {
                produk.focus()

                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Produk & qty terima tidak boleh kosong'
                })

            } else if (qtyTerima.val() < 1) {
                qtyTerima.focus()
                qtyTerima.val('')

                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Qty minimal 1'
                })
            //     else if (parseInt(qtyTerima.val()) > parseInt(stok.val())) {
            //     qtyTerima.focus()
            //     qtyTerima.val('')

            //     Swal.fire({
            //         icon: 'error',
            //         title: 'Error',
            //         text: 'Stok hanya tersisa ' + stok.val()
            //     })

            // }
            } else {

                // cek duplikasi produk
                $('input[name="produk[]"]').each(function() {
                    // cari index tr ke berapa
                    let index = $(this).parent().parent().index()

                    if ($(this).val() == produk.val()) {
                        // hapus tr berdasarkan index
                        tblCart.find('tbody tr:eq(' + index + ')').remove()

                        generateNo()
                    }
                })

                tblCart.find('tbody').append(`
                    <tr>
                        <td>${tblCart.find('tbody tr').length + 1}</td>
                        <td>
                            ${produk.find('option:selected').text()}
                            <input type="hidden" class="produk-hidden" name="produk[]" value="${produk.val()}">
                        </td>
                        <td>
                            ${unitProduk.val()}
                            <input type="hidden" class="unit-hidden" name="unit[]" value="${unitProduk.val()}">
                        </td>
                        <td>
                            ${qtyTerima.val()}
                            <input type="hidden" class="qty-terima-hidden" name="qty_terima[]" value="${qtyTerima.val()}">
                            <input type="hidden" class="stok-hidden" name="stok[]" value="${stok.val()}">
                        </td>
                        <td>
                            <button class="btn btn-warning btn-xs me-1 btn-edit" type="button">
                                <i class="fas fa-edit"></i>
                            </button>

                            <button class="btn btn-danger btn-xs btn-delete" type="button">
                                <i class="fas fa-times"></i>
                            </button>
                        </td>
                    </tr>
                `)

                generateNo()
                clearForm()
                cekTableLength()
                stok.val('')
                produk.focus()

            }
        })

        btnUpdate.click(function() {
            let index = $('#index-tr').val()

            if (!kode.val()) {
                kode.focus()

                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Kode tidak boleh kosong'
                })

            } else if (!tanggal.val()) {
                tanggal.focus()

                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Tanggal tidak boleh kosong'
                })

            } else if (!keterangan.val()) {
                keterangan.focus()

                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Keterangan tidak boleh kosong'
                })

            } else if (!produk.val() || !qtyTerima.val()) {
                produk.focus()

                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Produk & qty terima tidak boleh kosong'
                })

            } else if (qtyTerima.val() < 1) {
                qtyTerima.focus()
                qtyTerima.val('')

                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Qty minimal 1'
                })

            //     else if (parseInt(qtyTerima.val()) > parseInt(stok.val())) {
            //     qtyTerima.focus()
            //     qtyTerima.val('')

            //     Swal.fire({
            //         icon: 'error',
            //         title: 'Error',
            //         text: 'Stok hanya tersisa ' + stok.val()
            //     })

            // }

            } else {
                // cek duplikasi pas update
                $('input[name="produk[]"]').each(function(i) {
                    // i = index each
                    if ($(this).val() == produk.val() && i != index) {
                        tblCart.find('tbody tr:eq(' + i + ')').remove()
                    }
                })

                $('#tbl-cart tbody tr:eq(' + index + ')').html(`
                    <td></td>
                    <td>
                        ${produk.find('option:selected').text()}
                        <input type="hidden" class="produk-hidden" name="produk[]" value="${produk.val()}">
                    </td>
                    <td>
                        ${unitProduk.val()}
                        <input type="hidden" class="unit-hidden" name="unit[]" value="${unitProduk.val()}">
                    </td>
                    <td>
                        ${qtyTerima.val()}
                        <input type="hidden" class="qty-terima-hidden" name="qty_terima[]" value="${qtyTerima.val()}">
                        </td>
                        <input type="hidden" class="stok-hidden" name="stok[]" value="${stok.val()}">
                    <td>
                        <button class="btn btn-warning btn-xs me-1 btn-edit" type="button">
                            <i class="fas fa-edit"></i>
                        </button>

                        <button class="btn btn-danger btn-xs btn-delete" type="button">
                            <i class="fas fa-times"></i>
                        </button>
                    </td>
                `)

                clearForm()
                generateNo()

                btnUpdate.hide()
                btnAdd.show()
            }
        })

        $(document).on('click', '.btn-edit', function(e) {
            e.preventDefault()

            // ambil <tr> index
            let index = $(this).parent().parent().index()

            btnAdd.hide()

            btnUpdate.show()

            produk.val($('.produk-hidden:eq(' + index + ')').val())
            qtyTerima.val($('.qty-terima-hidden:eq(' + index + ')').val())
            unitProduk.val($('.unit-hidden:eq(' + index + ')').val())
            stok.val($('.stok-hidden:eq(' + index + ')').val())

            $('#index-tr').val(index)

            // console.log($('.stok-hidden:eq(' + index + ')').val());
        })

        $(document).on('click', '.btn-delete', function(e) {
            $(this).parent().parent().remove()

            generateNo()
            cekTableLength()
        })

        $('#form-bac').submit(function(e) {
            e.preventDefault()

            btnSave.prop('disabled', true)
            btnSave.addClass('disabled')
            btnSave.text('loading...')

            btnCancel.prop('disabled', true)
            btnCancel.addClass('disabled')
            btnCancel.text('loading...')

            $.ajax({
                type: 'POST',
                processData: false,
                contentType: false,
                url: '{{ route('new-bac-terima.store') }}',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                },
                data: new FormData(this),
                success: function(res) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Simpan data',
                        text: 'Berhasil'
                    }).then(function() {
                        window.location = '{{ route('new-bac-terima.index') }}'
                    })
                },
                error: function(xhr) {
                    btnSave.prop('disabled', false)
                    btnSave.removeClass('disabled')
                    btnSave.text('Simpan')

                    btnCancel.prop('disabled', false)
                    btnCancel.removeClass('disabled')
                    btnCancel.text('Cancel')

                    $('#p-msg').text(xhr.responseJSON.message)

                    let errorMsg = []
                    $.each(xhr.responseJSON.errors, function(index, value) {
                        errorMsg.push(`<li><p class="mb-0">${value}</p></li>`)
                    })

                    $('#ul-msg').html(errorMsg)

                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: 'Something went wrong!'
                    })
                }
            })
        })

        $('#kode, #tanggal, #keterangan, #produk, #qty_terima, #nama, #file').on('change keyup', function() {
            cekForm()
            // cekTableLength()
        })

        $('#area-button').on('mouseover mouseenter mouseleave mousemove', function() {
            cekForm()
            // cekTableLength()
        })

        function getKode() {
            $.ajax({
                url: '/inventory/new-bac-terima/generate-kode/' + tanggal.val(),
                method: 'GET',
                success: function(res) {
                    setTimeout(() => {
                        kode.val(res.kode)
                    }, 500)
                }
            })
        }

        function cekForm() {
            if (!$('#nama').val() ||
                !$('#file').val() ||
                !$('#kode').val() ||
                !$('#tanggal').val() ||
                !$('#keterangan').val()
            ) {
                disableButton()
            } else {
                enableButton()
            }
        }

        function cekTableLength() {
            let cekTblCart = tblCart.find('tbody tr').length
            let cekTblFile = $('#tbl-file tbody tr').length

            if (cekTblCart > 0 || cekTblFile > 0) {
                enableButton()
            } else {
                disableButton()
            }
        }

        function disableButton() {
            btnSave.prop('disabled', true)
            btnCancel.prop('disabled', true)
        }

        function enableButton() {
            btnSave.prop('disabled', false)
            btnCancel.prop('disabled', false)
        }

        function clearForm() {
            kodeProduk.val('')
            produk.val('')
            unitProduk.val('')
            qtyTerima.val('')
        }

        function generateNo() {
            let no = 1

            tblCart.find('tbody tr').each(function() {
                $(this).find('td:nth-child(1)').html(no)
                no++
            })
        }
    </script>

    {{-- file --}}
    <script>
        const tableFile = $('#tbl-file tbody')

        $('#btn-add-file').click(function() {
            let tr = ` <tr>
                <td>
                    <div class="form-group">
                    <input class="form-control nama" type="text" name="nama[]" placeholder="Nama File" required />
                    </div>
                </td>
                <td>
                    <div class="form-group">
                    <input class="form-control file" type="file" name="file[]" required />
                    </div>
                </td>
                <td>
                    <button class="btn btn-danger btn-delete-file" type="button">
                        <i class="fas fa-times"></i>
                    </button>
                </td>
            </tr>`

            tableFile.append(tr)
        })

        $(document).on('click', '.btn-delete-file', function() {
            if (tableFile.find('tr').length > 1) {
                $(this).parent().parent().remove()
            }
        })
    </script>
@endpush
