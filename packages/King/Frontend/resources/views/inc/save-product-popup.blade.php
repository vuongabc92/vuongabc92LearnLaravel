<!-- Modal -->
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="_fwfl _bgw _r3 modal-content">
            <div class=" _fwfl modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title _tg5 _fs17" id="myModalLabel">Add new product</h4>
            </div>
            <div class="_fwfl modal-body">
                {!! Form::open(['method' => 'POST', 'class' => '_fwfl', 'id' => '']) !!}
                <div class="_fwfl setting-form-group">
                    <label class="_fwfl setting-form-label" for="name" data-title="Name">Name</label>
                    {!! Form::text('name', '', ['class' => 'setting-form-field', 'id' => 'name', 'maxlength' => '250']) !!}
                </div>
                <div class="_fwfl setting-form-group">
                    <label class="_fwfl setting-form-label" for="price" data-title="Price">Price</label>
                    {!! Form::text('price', '', ['class' => 'setting-form-field', 'id' => 'price', 'maxlength' => '250']) !!}
                </div>
                <div class="_fwfl setting-form-group">
                    <label class="_fwfl setting-form-label" for="old-price" data-title="Old price">Old price</label>
                    {!! Form::text('price', '', ['class' => 'setting-form-field', 'id' => 'old-price', 'maxlength' => '250']) !!}
                </div>
                <div class="_fwfl setting-form-group">
                    <label class="_fwfl setting-form-label" for="description" data-title="Description">Description</label>
                    {!! Form::textarea('price', '', ['class' => 'setting-form-field', 'id' => 'description', 'maxlength' => '1000']) !!}
                </div>
                {!! Form::close() !!}
            </div>
            <div class="modal-footer _fwfl">
                <button type="reset" class="_fr btn _btn _btn-gray close-form-pass" data-reset-form="#change-pass-form">{{ _t('cancel') }}</button>
                <button type="submit" class="_fr _mr10 btn _btn _btn-blue1 _save-btn">
                    <img class="loading-in-btn" src="{{ asset('packages/king/frontend/images/loading-white-blue1.gif') }}" />
                    <b class="btn-text">{{ _t('save') }}</b>
                    <i class="fa fa-check _dn"></i>
                </button>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->