<template>
  <div>
    <h1>Create Server</h1>
    <div class="jumbotron d-flex align-items-center">
      <div class="container px-50">
        <div>
          <form>
            <div class="form-group row">
              <label for="assetId" class="col-sm-2 col-form-label">Asset ID</label>
              <div class="col-sm-10">
                <input type="text" class="form-control" id="assetId"/>
              </div>
            </div>
            <div class="form-group row">
              <label for="brand" class="col-sm-2 col-form-label">Brand</label>
              <div class="col-sm-10">
                <input type="text" class="form-control" id="brand"/>
              </div>
            </div>
            <div class="form-group row">
              <label for="name" class="col-sm-2 col-form-label">Name</label>
              <div class="col-sm-10">
                <input type="text" class="form-control" id="name"/>
              </div>
            </div>
            <div class="form-group row">
              <label for="price" class="col-sm-2 col-form-label">Price</label>
              <div class="col-sm-10">
                <input type="text" class="form-control" id="price"/>
              </div>
            </div>
            <div class="row">
              <div class="col-4">
                <div class="form-group row">
                  <label for="ram" class="col-sm-2 col-form-label">Ram</label>
                  <div class="col-sm-10">
                    <select class="form-control" id="ram">
                      <option>1</option>
                      <option>2</option>
                      <option>3</option>
                      <option>4</option>
                      <option>5</option>
                    </select>
                  </div>
                </div>
              </div>
              <div class="col-4">
                <div class="form-group row">
                  <label for="quantity" class="col-sm-2 col-form-label">Quantity</label>
                  <div class="col-sm-10">
                    <input type="text" class="form-control" id="quantity"/>
                  </div>
                </div>
              </div>
              <div class="col-4">
                <div class="form-group row">
                  <div class="col-sm-10">
                    <button class="btn btn-primary mb-2" @click="navigateToServer(server.assetId)" id="addRam">Add</button>
                  </div>
                </div>
              </div>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</template>