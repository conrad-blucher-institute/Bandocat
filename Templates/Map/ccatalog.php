<form>
    <!-- Text Boxes -->
    <div class="form-group">
        <div class="form-row">
            <div class="col">
                <label for="libIndex">Library Index</label>
                <input type="text" class="form-control" id="libIndex" placeholder="Enter Library Index" required>
                <small id="smIndex" class="form-text text-muted">This is the library index of the document</small>
            </div>

            <div class="col">
                <label for="documentTitle">Document Title</label>
                <input type="text" class="form-control" id="documentTitle" placeholder="Enter Document Title" required>
                <small id="smTitle" class="form-text text-muted">This is the title of the document</small>
            </div>
        </div>
    </div>

    <div class="form-group">
        <div class="form-row">
            <div class="col">
                <label for="subTitle">Document Subtitle</label>
                <input type="text" class="form-control" id="subTitle" placeholder="Enter Document Subtitle">
                <small id="smSubTitle" class="form-text text-muted">This is the subtitle of the document</small>
            </div>

            <div class="col">
                <label for="mapScale">Map Scale</label>
                <input type="text" class="form-control" id="mapScale" placeholder="Enter Map Scale">
                <small id="smMapScale" class="form-text text-muted">Map Scale example: 1 in = 1000 ft</small>
            </div>
        </div>
    </div>

    <div class="form-group">
        <div class="form-row">
            <div class="col">
                <label for="compName">Company Name</label>
                <input type="text" class="form-control" id="compName" placeholder="Enter Company Name">
                <small id="smCompName" class="form-text text-muted">The name of the company on the document</small>
            </div>

            <div class="col-md-6">
                <label for="lstCustomer">Customer Name</label>
                <input type="text" class="form-control" id="lstCustomer" placeholder="Enter Customer Name">
                <small id="smCustName" class="form-text text-muted">The name of the customer on the document</small>
            </div>
        </div>
    </div>

    <div class="form-group">
        <div class="form-row">
            <div class="col">
                <label for="fieldNumber">Field Book Number</label>
                <input type="text" class="form-control" id="fieldNumber" placeholder="Enter Field Book Number">
                <small id="smFieldNumber" class="form-text text-muted">The book number on the field book</small>
            </div>

            <div class="col">
                <label for="fieldPage">Field Book Page</label>
                <input type="text" class="form-control" id="fieldPage" placeholder="Enter Field Book Page">
                <small id="smFieldPage" class="form-text text-muted">The book page</small>
            </div>
        </div>
    </div>

    <div class="form-group">
        <div class="form-row">
            <div class="col">
                <label for="docType">Document Type</label>
                <input type="text" class="form-control" id="docType" placeholder="Enter Document Type">
                <small id="smFieldPage" class="form-text text-muted">The type of document, example: Map</small>
            </div>
        </div>
    </div>

    <!-- RAD BUTTONS -->
    <div class="form-group">
        <div class="form-row">
            <div class="col">
                <div class="form-group">
                    <label data-toggle="tooltip" title="This is to signal if it is a map">Map</label>
                    <div class="form-check">
                        <label class="form-check-label">
                            <input type="radio" class="form-check-input" name="map" id="optionsRadios1" value="true" checked>
                            Yes
                        </label>
                    </div>
                    <div class="form-check">
                        <label class="form-check-label">
                            <input type="radio" class="form-check-input" name="map" id="optionsRadios2" value="false">
                            No
                        </label>
                    </div>
                </div>
            </div>

            <div class="col">
                <div class="form-group">
                    <label data-toggle="tooltip" title="This is to signal if review is needed">Needs Review</label>
                    <div class="form-check">
                        <label class="form-check-label">
                            <input type="radio" class="form-check-input" name="review" id="optionsRadios3" value="true" checked>
                            Yes
                        </label>
                    </div>
                    <div class="form-check">
                        <label class="form-check-label">
                            <input type="radio" class="form-check-input" name="review" id="optionsRadios4" value="false">
                            No
                        </label>
                    </div>
                </div>
            </div>

            <div class="col">
                <div class="form-group">
                    <label data-toggle="tooltip" title="This is to signal if it has a north arrow">Has North Arrow</label>
                    <div class="form-check">
                        <label class="form-check-label">
                            <input type="radio" class="form-check-input" name="arrow" id="optionsRadios5" value="true" checked>
                            Yes
                        </label>
                    </div>
                    <div class="form-check">
                        <label class="form-check-label">
                            <input type="radio" class="form-check-input" name="arrow" id="optionsRadios6" value="false">
                            No
                        </label>
                    </div>
                </div>
            </div>

            <div class="col">
                <div class="form-group">
                    <label data-toggle="tooltip" title="This is a street(s) is present">Has Streets</label>
                    <div class="form-check">
                        <label class="form-check-label">
                            <input type="radio" class="form-check-input" name="steets" id="optionsRadios7" value="true">
                            Yes
                        </label>
                    </div>
                    <div class="form-check">
                        <label class="form-check-label">
                            <input type="radio" class="form-check-input" name="steets" id="optionsRadios8" value="false" checked>
                            No
                        </label>
                    </div>
                </div>
            </div>
            <div class="col">
                <div class="form-group">
                    <label data-toggle="tooltip" title="This is to signal if the coast line is present">Has Coast</label>
                    <div class="form-check">
                        <label class="form-check-label">
                            <input type="radio" class="form-check-input" name="coast" id="optionsRadios5" value="true">
                            Yes
                        </label>
                    </div>
                    <div class="form-check">
                        <label class="form-check-label">
                            <input type="radio" class="form-check-input" name="coast" id="optionsRadios6" value="false" checked>
                            No
                        </label>
                    </div>
                </div>
            </div>

            <div class="col">
                <div class="form-group">
                    <label data-toggle="tooltip" title="This is to signal if a point of interest is present">Has POI</label>
                    <div class="form-check">
                        <label class="form-check-label">
                            <input type="radio" class="form-check-input" name="poi" id="optionsRadios1" value="true">
                            Yes
                        </label>
                    </div>
                    <div class="form-check">
                        <label class="form-check-label">
                            <input type="radio" class="form-check-input" name="poi" id="optionsRadios2" value="false" checked>
                            No
                        </label>
                    </div>
                </div>
            </div>

            <div class="col">
                <div class="form-group">
                    <label data-toggle="tooltip" title="This is to signal if the coordinates are visible">Has Coordinates</label>
                    <div class="form-check">
                        <label class="form-check-label">
                            <input type="radio" class="form-check-input" name="coord" id="optionsRadios1" value="true">
                            Yes
                        </label>
                    </div>
                    <div class="form-check">
                        <label class="form-check-label">
                            <input type="radio" class="form-check-input" name="coord" id="optionsRadios2" value="false" checked>
                            No
                        </label>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="form-row">
        <div class="col-md-6">
            <button type="submit" class="btn btn-primary" style="width: 100%;">Submit</button>
        </div>
        <div class="col-md-6">
            <button type="button" class="btn btn-secondary" style="width: 100%;">Reset</button>
        </div>
    </div>
</form>