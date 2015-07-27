<!-- Modal -->
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="_fwfl _bgw _r3 modal-content">
            <div class=" _fwfl modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title _tg5 _fs17" id="myModalLabel">Add new product</h4>
            </div>
            <div class="_fwfl modal-body">
                {!! Form::open(['route' => 'front_save_product', 'method' => 'POST', 'class' => '_fwfl', 'id' => 'save-product-form']) !!}
                <div class="_fwfl setting-form-group">
                    <label class="_fwfl setting-form-label" data-title="Image">
                        Image <span class="_tr6">*</span>
                    </label>
                    <div class="_fwfl">
                        <span class="_r3 add-product-image product-img-1" data-event-trigger="#product-image1-file" data-event="click|click">
                            <i class="fa fa-plus"></i>
                        </span>
                        <span class="_r3 add-product-image product-img-2" data-event-trigger="#product-image2-file" data-event="click|click">
                            <i class="fa fa-plus"></i>
                        </span>
                        <span class="_r3 add-product-image product-img-3" data-event-trigger="#product-image3-file" data-event="click|click">
                            <i class="fa fa-plus"></i>
                        </span>
                        <span class="_r3 add-product-image product-img-4 add-product-image-last" data-event-trigger="#product-image4-file" data-event="click|click">
                            <i class="fa fa-plus"></i>
                        </span>
                    </div>
                </div>
                <div class="_fwfl setting-form-group">
                    <label class="_fwfl setting-form-label" for="name" data-title="Name">
                        Name <span class="_tr6">*</span>
                    </label>
                    {!! Form::text('name', '', ['class' => 'setting-form-field', 'id' => 'name', 'maxlength' => '250']) !!}
                </div>
                <div class="_fwfl setting-form-group">
                    <label class="_fwfl setting-form-label" for="price" data-title="Price">
                        Price <span class="_tr6">*</span>
                    </label>
                    {!! Form::text('price', '', ['class' => 'setting-form-field', 'id' => 'price', 'maxlength' => '250']) !!}
                </div>
                <div class="_fwfl setting-form-group">
                    <label class="_fwfl setting-form-label" for="old-price" data-title="Old price">
                        Old price <span class="_tr6">*</span>
                    </label>
                    {!! Form::text('old_price', '', ['class' => 'setting-form-field', 'id' => 'old-price', 'maxlength' => '250']) !!}
                </div>
                <div class="_fwfl setting-form-group">
                    <label class="_fwfl setting-form-label" for="description" data-title="Description">
                        Description <span class="_tr6">*</span>
                    </label>
                    {!! Form::textarea('description', '', ['class' => 'product-description setting-form-field', 'id' => 'description', 'row' => '4']) !!}
                </div>
                {!! Form::close() !!}
            </div>
            <div class="modal-footer _fwfl">
                <button type="reset" class="_fr btn _btn _btn-gray close-form-pass">{{ _t('cancel') }}</button>
                <button type="submit" class="_fr _mr10 btn _btn _btn-blue1 _save-btn">
                    <img class="loading-in-btn" src="{{ asset('packages/king/frontend/images/loading-white-blue1.gif') }}" />
                    <b class="btn-text">{{ _t('save') }}</b>
                    <i class="fa fa-check _dn"></i>
                </button>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<div class="_fwfl _dn">
    {!! Form::open(['route' => 'front_product_image', 'files' => true, 'method' => 'POST', 'id' => 'product-image1-form', 'data-product-image']) !!}
        {!! Form::file('__product', ['class' => 'field-file-hidden', 'id' => 'product-image1-file', 'accept' => 'image/*', 'data-event-trigger' => '#product-image1-form', 'data-event' => 'change|submit']) !!}
        {!! Form::hidden('order', 1) !!}
    {!! Form::close() !!}

    {!! Form::open(['route' => 'front_product_image', 'files' => true, 'method' => 'POST', 'id' => 'product-image2-form', 'data-product-image']) !!}
        {!! Form::file('__product', ['class' => 'field-file-hidden', 'id' => 'product-image2-file', 'accept' => 'image/*', 'data-event-trigger' => '#product-image2-form', 'data-event' => 'change|submit']) !!}
        {!! Form::hidden('order', 2) !!}
    {!! Form::close() !!}

    {!! Form::open(['route' => 'front_product_image', 'files' => true, 'method' => 'POST', 'id' => 'product-image3-form', 'data-product-image']) !!}
        {!! Form::file('__product', ['class' => 'field-file-hidden', 'id' => 'product-image3-file', 'accept' => 'image/*', 'data-event-trigger' => '#product-image3-form', 'data-event' => 'change|submit']) !!}
        {!! Form::hidden('order', 3) !!}
    {!! Form::close() !!}

    {!! Form::open(['route' => 'front_product_image', 'files' => true, 'method' => 'POST', 'id' => 'product-image4-form', 'data-product-image']) !!}
        {!! Form::file('__product', ['class' => 'field-file-hidden', 'id' => 'product-image4-file', 'accept' => 'image/*', 'data-event-trigger' => '#product-image4-form', 'data-event' => 'change|submit']) !!}
        {!! Form::hidden('order', 4) !!}
    {!! Form::close() !!}
</div>