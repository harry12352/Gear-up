<div class="col-12 col-md-4 col-lg-3 px-lg-2 d-flex w-100 @if(count($user->products) > 0) mb-3 @endif add_new_product_card" @if(count($user->products) == 0) style="height: 416px" @endif>
    <a href="{{ route('products.create', ['user' => $user]) }}" class="text-decoration-none align-items-stretch d-flex w-100 text-center">
        <div class="card product-create-card w-100">
            <div class="add-product d-table h-100 w-100">
                <div class="h-100 align-middle d-table-cell w-100">
                    <svg xmlns="http://www.w3.org/2000/svg" width="44" height="44" viewBox="0 0 44 44">
                        <path id="Add" style="fill-rule: evenodd;" d="M453,1243a22,22,0,1,0,22,22A22.025,22.025,0,0,0,453,1243Zm0,41a19,19,0,1,1,19-19A19,19,0,0,1,453,1284Zm8-20h-6v-6a1.5,1.5,0,0,0-3,0v6h-6a1.5,1.5,0,0,0,0,3h6v6a1.5,1.5,0,0,0,3,0v-6h6A1.5,1.5,0,0,0,461,1264Z" transform="translate(-431 -1243)"/>
                    </svg>
                    <h5 class="mt-4">Add New Product</h5>
                </div>
            </div>
        </div>
    </a>
</div>
