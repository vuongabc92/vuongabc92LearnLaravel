<!-- Modal -->
<div class="modal fade" id="add-product-modal" tabindex="-1" role="dialog" aria-labelledby="addProductModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="_fwfl _bgw _r3 modal-content">
            {!! Form::open(['route' => 'front_save_product', 'method' => 'POST', 'class' => '_fwfl', 'id' => 'save-product-form', 'data-save-product' => 'product_image_1|id|name|price|old_price|description']) !!}
            {!! Form::hidden('id', '', ['id' => 'product-id']) !!}
            <div class=" _fwfl modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title _tg5 _fs17" id="addProductModalLabel" data-add-title="{{ _t('add_new_product') }}" data-edit-title="{{ _t('edit_product') }}">{{ _t('add_new_product') }}</h4>
            </div>
            <div class="_fwfl modal-body">
                <div class="_fwfl setting-form-group">
                    <div class="_fwfl">
                        <label class="_fl setting-form-label" data-title='{{ _t('product_image') }} <span class="_tr6">*</span>'>
                            <span class="_fl">{{ _t('product_image') }} <span class="_tr6">*</span></span>
                        </label>
                        <img class="_fl _ml10 _mt2 _dn product-img-loading" src="{{ asset('packages/king/frontend/images/loading-blue-white-16x16.gif') }}" />
                        <span class="_fr _fs12 _mt3 _tr6 add-product-image-error"></span>
                        {!! Form::hidden('product_image_1', '', ['class' => 'product-image-hidden', 'id' => 'product-image-1', 'autocomplete' => 'off']) !!}
                        {!! Form::hidden('product_image_2', '', ['class' => 'product-image-hidden', 'id' => 'product-image-2', 'autocomplete' => 'off']) !!}
                        {!! Form::hidden('product_image_3', '', ['class' => 'product-image-hidden', 'id' => 'product-image-3', 'autocomplete' => 'off']) !!}
                        {!! Form::hidden('product_image_4', '', ['class' => 'product-image-hidden', 'id' => 'product-image-4', 'autocomplete' => 'off']) !!}
                        {!! Form::hidden('reset_product_image', route('front_product_del_temp_img'), ['id' => 'reset-product-image']) !!}
                    </div>
                    <div class="_fwfl">
                        <div class="_r3 add-product-image product-img-1" data-event-trigger="#product-image1-file" data-event="click|click">
                            <span class="_fwfl _fh">
                                <i class="fa fa-plus"></i>
                            </span>
                        </div>
                        <div class="_r3 add-product-image product-img-2" data-event-trigger="#product-image2-file" data-event="click|click">
                            <span class="_fwfl _fh">
                                <i class="fa fa-plus"></i>
                            </span>
                        </div>
                        <div class="_r3 add-product-image product-img-3" data-event-trigger="#product-image3-file" data-event="click|click">
                            <span class="_fwfl _fh">
                                <i class="fa fa-plus"></i>
                            </span>
                        </div>
                        <div class="_r3 add-product-image product-img-4 add-product-image-last" data-event-trigger="#product-image4-file" data-event="click|click">
                            <span class="_fwfl _fh">
                                <i class="fa fa-plus"></i>
                            </span>
                        </div>
                    </div>
                </div>
                <div class="_fwfl setting-form-group">
                    <label class="_fwfl setting-form-label" for="name" data-title="{{ _t('product_name') }} <span class='_tr6'>*</span>">
                        {{ _t('product_name') }} <span class="_tr6">*</span>
                    </label>
                    {!! Form::text('name', '', ['class' => 'setting-form-field', 'id' => 'name', 'maxlength' => '250']) !!}
                </div>
                <div class="_fwfl setting-form-group">
                    <label class="_fwfl setting-form-label" for="price" data-title="{{ _t('product_price') }} <span class='_tr6'>*</span>">
                        {{ _t('product_price') }} <span class="_tr6">*</span>
                    </label>
                    {!! Form::text('price', '', ['class' => 'setting-form-field', 'id' => 'price', 'maxlength' => '250']) !!}
                </div>
                <div class="_fwfl setting-form-group">
                    <label class="_fwfl setting-form-label" for="old-price" data-title="{{ _t('product_old_price') }}">
                        {{ _t('product_old_price') }}
                    </label>
                    {!! Form::text('old_price', '', ['class' => 'setting-form-field', 'id' => 'old-price', 'maxlength' => '250']) !!}
                </div>
                <div class="_fwfl setting-form-group">
                    <label class="_fwfl setting-form-label" for="description" data-title="{{ _t('product_desc') }} <span class='_tr6'>*</span>">
                        {{ _t('product_desc') }} <span class="_tr6">*</span>
                    </label>
                    {!! Form::textarea('description', '', ['class' => 'product-description setting-form-field', 'id' => 'description', 'row' => '4']) !!}
                </div>
            </div>
            <div class="modal-footer _fwfl">
                <button type="reset" class="_fr btn _btn _btn-gray add-product-reset-btn" data-reset-form="#save-product-form" data-dismiss="modal">{{ _t('cancel') }}</button>
                <button type="submit" class="_fr _mr10 btn _btn _btn-blue1 _save-btn">
                    <img class="loading-in-btn" src="{{ asset('packages/king/frontend/images/loading-white-blue-24x24.gif') }}" />
                    <b class="btn-text">{{ _t('save') }}</b>
                    <i class="fa fa-check _dn"></i>
                </button>
            </div>
            {!! Form::close() !!}
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<div class="_fwfl _dn">
    {!! Form::open(['route' => 'front_product_image', 'files' => true, 'method' => 'POST', 'id' => 'product-image1-form', 'data-product-image']) !!}
        {!! Form::file('__product', ['class' => 'field-file-hidden', 'id' => 'product-image1-file', 'accept' => 'image/*', 'data-event-trigger' => '#product-image1-form', 'data-event' => 'change|submit']) !!}
        {!! Form::hidden('order', 1) !!}
        {!! Form::hidden('current_image', '', ['id' => 'current-image', 'autocomplete' => 'off']) !!}
    {!! Form::close() !!}

    {!! Form::open(['route' => 'front_product_image', 'files' => true, 'method' => 'POST', 'id' => 'product-image2-form', 'data-product-image']) !!}
        {!! Form::file('__product', ['class' => 'field-file-hidden', 'id' => 'product-image2-file', 'accept' => 'image/*', 'data-event-trigger' => '#product-image2-form', 'data-event' => 'change|submit']) !!}
        {!! Form::hidden('order', 2) !!}
        {!! Form::hidden('current_image', '', ['id' => 'current-image', 'autocomplete' => 'off']) !!}
    {!! Form::close() !!}

    {!! Form::open(['route' => 'front_product_image', 'files' => true, 'method' => 'POST', 'id' => 'product-image3-form', 'data-product-image']) !!}
        {!! Form::file('__product', ['class' => 'field-file-hidden', 'id' => 'product-image3-file', 'accept' => 'image/*', 'data-event-trigger' => '#product-image3-form', 'data-event' => 'change|submit']) !!}
        {!! Form::hidden('order', 3) !!}
        {!! Form::hidden('current_image', '', ['id' => 'current-image', 'autocomplete' => 'off']) !!}
    {!! Form::close() !!}

    {!! Form::open(['route' => 'front_product_image', 'files' => true, 'method' => 'POST', 'id' => 'product-image4-form', 'data-product-image']) !!}
        {!! Form::file('__product', ['class' => 'field-file-hidden', 'id' => 'product-image4-file', 'accept' => 'image/*', 'data-event-trigger' => '#product-image4-form', 'data-event' => 'change|submit']) !!}
        {!! Form::hidden('order', 4) !!}
        {!! Form::hidden('current_image', '', ['id' => 'current-image', 'autocomplete' => 'off']) !!}
    {!! Form::close() !!}
</div>