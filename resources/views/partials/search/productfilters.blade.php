<div class="container">
    <div class="row">
        <h2 id="results-text" class="col-md-4 col-sm-12 text-center">No results</h2>
        <div class="col-md-2 col-sm-6 dropdown">
            <div class="h-100" type="button" id="dropdownCategories" data-bs-toggle="dropdown" aria-expanded="false">
                <div class="row justify-content-between align-items-center h-100">
                    <h4 class="text-center">Categories<i class="bi bi-caret-down-fill" style="font-size: 0.7em;"></i></h4>
                </div>
            </div>
            <div class="dropdown-menu p-2" aria-labelledby="dropdownCategories">
                <ul class="list-group">
                @foreach ($categories as $category)
                    @include('partials.search.category', ['category' => $category, 'level' => 0])
                @endforeach
                </ul>
            </div>
        </div>
        <div class="col-md-2 col-sm-6 dropdown">
            <div class="h-100" type="button" id="dropdownPrice" data-bs-toggle="dropdown" aria-expanded="false">
                <div class="row justify-content-between align-items-center h-100">
                    <h4 class="text-center">Price<i class="bi bi-caret-down-fill" style="font-size: 0.7em;"></i></h4>
                </div>
            </div>
            <div class="dropdown-menu p-2" aria-labelledby="dropdownPrice">
                <label for="price-min">Min</label>
                <input name="price-min" id="price-min" type="number" step="0.01" min="0" class="form-control mb-2"
                    placeholder="Min Price" aria-label="Min Price">
                <label for="price-max">Max</label>
                <input name="price-max" id="price-max" type="number" step="0.01" min="0" class="form-control col-lg-6 col-md-12"
                    placeholder="Max Price" aria-label="Max Price">
            </div>
        </div>



        <div class="col-md-2 col-sm-6 dropdown">
            <div class="h-100" type="button" id="dropdownRating" data-bs-toggle="dropdown" aria-expanded="false">
                <div class="row justify-content-between align-items-center h-100">
                    <h4 class="text-center">Rating<i class="bi bi-caret-down-fill" style="font-size: 0.7em;"></i></h4>
                </div>
            </div>
            <div class="dropdown-menu p-2" aria-labelledby="dropdownRating">
                <label for="rate-min">Min</label>
                <input name="rate-min" id="rate-min" type="number" step="0.01" min="0" max="5" class="form-control mb-2"
                    placeholder="Min Rate" aria-label="Min Rate">
                <label for="rate-max">Max</label>
                <input name="rate-max" id="rate-max" type="number" step="0.01" min="0" max="5"
                    class="form-control col-lg-6 col-md-12" placeholder="Max Rate" aria-label="Max Rate">
            </div>
        </div>

        <div class="col-md-2 col-sm-6 dropdown">
            <div class="h-100" type="button" id="dropdownSort" data-bs-toggle="dropdown" aria-expanded="false">
                <div class="row justify-content-between align-items-center h-100">
                    <h4 class="text-center">Sort Results<i class="bi bi-caret-down-fill" style="font-size: 0.7em;"></i></h4>
                </div>
            </div>
            <div class="dropdown-menu p-2" aria-labelledby="dropdownSort">
                <div class="form-check mb-2">
                    <input group="sort-input" unique name="price-asc" id="price-asc" class="form-check-input sort-checkbox" type="checkbox"
                        aria-label="Lowest Price First">
                    <label class="form-check-label" for="price-asc">Lowest Price First</label>
                </div>
        
                <div class="form-check mb-2">
                    <input group="sort-input" unique name="price-desc" id="price-desc" class="form-check-input sort-checkbox" type="checkbox"
                        aria-label="Lowest Price First">
                    <label class="form-check-label" for="price-desc">Highest Price First</label>
                </div>
        
                <div class="form-check mb-2">
                    <input group="sort-input" unique name="rate-asc" id="rate-asc" class="form-check-input sort-checkbox" type="checkbox"
                        aria-label="Lowest Price First">
                    <label class="form-check-label" for="rate-asc">Lowest Rating First</label>
                </div>
        
                <div class="form-check">
                    <input group="sort-input" unique name="rate-desc" id="rate-desc" class="form-check-input sort-checkbox" type="checkbox"
                        aria-label="Highest Rating First">
                    <label class="form-check-label" for="rate-desc">Highest Rating First</label>
                </div>
            </div>
        </div>
    </div>
    <div class="row align-items-center">
        <div class="col-md-4 col-sm-12 d-flex justify-content-center my-md-0 my-4">
            <button type="reset" class="btn btn-primary col-lg-8 col-md-12 w-50">
                Reset Filters
            </button>
        </div>
        <div class="col-md-2 col-sm-6 text-center d-flex align-items-center justify-content-center flex-column">
            <h5 class="m-0">Selected Categories: </h5>
            <p class="m-0 px-2" id="filter-categories-text">None</p>
        </div>
        <div class="col-md-2 col-sm-6 text-center d-flex align-items-center justify-content-center flex-column">
            <h5 class="m-0">Price Range: </h5>
            <p class="m-0 px-2" id="filter-price-text">None</p>
        </div>
        <div class="col-md-2 col-sm-6 text-center d-flex align-items-center justify-content-center flex-column">
            <h5 class="m-0">Rating Range: </h5>
            <p class="m-0 px-2" id="filter-rating-text">None</p>
        </div>
        <div class="col-md-2 col-sm-6 text-center d-flex align-items-center justify-content-center flex-column">
            <h5 class="m-0">Sorted By: </h5>
            <p class="m-0 px-2" id="filter-sort-text">None</p>
        </div>
    </div>
</div>