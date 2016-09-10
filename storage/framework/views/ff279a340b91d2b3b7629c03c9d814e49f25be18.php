<?php $__env->startSection('content'); ?>

<div class="parkinglot-create">
    <div class="row">
      <form class="form-horizontal" role="form" method="POST" action="<?php echo e(url('home/store')); ?>">
              <?php echo e(csrf_field()); ?>


              <div class="form-group<?php echo e($errors->has('title') ? ' has-error' : ''); ?>">
                  <label for="title" class="col-md-4 control-label">Title</label>

                  <div class="col-md-6">
                      <input id="title" type="text" class="form-control" name="title" value="<?php echo e(old('title')); ?>" autofocus>

                      <?php if($errors->has('title')): ?>
                          <span class="help-block">
                              <strong><?php echo e($errors->first('title')); ?></strong>
                          </span>
                      <?php endif; ?>
                  </div>
              </div>

              <div class="form-group<?php echo e($errors->has('type') ? ' has-error' : ''); ?>">
                  <label for="type" class="col-md-4 control-label">Type</label>

                  <div class="col-md-6">
                      <input id="type" type="text" class="form-control" name="type" value="<?php echo e(old('type')); ?>">

                      <?php if($errors->has('type')): ?>
                          <span class="help-block">
                              <strong><?php echo e($errors->first('type')); ?></strong>
                          </span>
                      <?php endif; ?>
                  </div>
              </div>

              <div class="form-group<?php echo e($errors->has('address') ? ' has-error' : ''); ?>">
                  <label for="address" class="col-md-4 control-label">Type</label>

                  <div class="col-md-6">
                      <input id="address" type="text" class="form-control" name="address" value="<?php echo e(old('address')); ?>">

                      <?php if($errors->has('address')): ?>
                          <span class="help-block">
                              <strong><?php echo e($errors->first('address')); ?></strong>
                          </span>
                      <?php endif; ?>
                  </div>
              </div>

              <div class="form-group<?php echo e($errors->has('city') ? ' has-error' : ''); ?>">
                  <label for="city" class="col-md-4 control-label">City</label>

                  <div class="col-md-6">
                      <input id="city" type="text" class="form-control" name="city" value="<?php echo e(old('city')); ?>">

                      <?php if($errors->has('city')): ?>
                          <span class="help-block">
                              <strong><?php echo e($errors->first('city')); ?></strong>
                          </span>
                      <?php endif; ?>
                  </div>
              </div>

              <div class="form-group<?php echo e($errors->has('state') ? ' has-error' : ''); ?>">
                  <label for="state" class="col-md-4 control-label">City</label>

                  <div class="col-md-6">
                      <input id="state" type="text" class="form-control" name="state" value="<?php echo e(old('state')); ?>">

                      <?php if($errors->has('state')): ?>
                          <span class="help-block">
                              <strong><?php echo e($errors->first('state')); ?></strong>
                          </span>
                      <?php endif; ?>
                  </div>
              </div>

              <div class="form-group<?php echo e($errors->has('zipcode') ? ' has-error' : ''); ?>">
                  <label for="zipcode" class="col-md-4 control-label">City</label>

                  <div class="col-md-6">
                      <input id="zipcode" type="text" class="form-control" name="zipcode" value="<?php echo e(old('zipcode')); ?>">

                      <?php if($errors->has('zipcode')): ?>
                          <span class="help-block">
                              <strong><?php echo e($errors->first('zipcode')); ?></strong>
                          </span>
                      <?php endif; ?>
                  </div>
              </div>

              <div class="form-group<?php echo e($errors->has('country') ? ' has-error' : ''); ?>">
                  <label for="country" class="col-md-4 control-label">Country</label>

                  <div class="col-md-6">
                      <input id="country" type="text" class="form-control" name="country" value="<?php echo e(old('country')); ?>">

                      <?php if($errors->has('country')): ?>
                          <span class="help-block">
                              <strong><?php echo e($errors->first('country')); ?></strong>
                          </span>
                      <?php endif; ?>
                  </div>
              </div>

              <div class="form-group<?php echo e($errors->has('latitude') ? ' has-error' : ''); ?>">
                  <label for="latitude" class="col-md-4 control-label">Latitude</label>

                  <div class="col-md-6">
                      <input id="latitude" type="text" class="form-control" name="latitude" value="<?php echo e(old('latitude')); ?>">

                      <?php if($errors->has('latitude')): ?>
                          <span class="help-block">
                              <strong><?php echo e($errors->first('latitude')); ?></strong>
                          </span>
                      <?php endif; ?>
                  </div>
              </div>

              <div class="form-group<?php echo e($errors->has('longitude') ? ' has-error' : ''); ?>">
                  <label for="longitude" class="col-md-4 control-label">Longitude</label>

                  <div class="col-md-6">
                      <input id="longitude" type="text" class="form-control" name="longitude" value="<?php echo e(old('longitude')); ?>">

                      <?php if($errors->has('longitude')): ?>
                          <span class="help-block">
                              <strong><?php echo e($errors->first('longitude')); ?></strong>
                          </span>
                      <?php endif; ?>
                  </div>
              </div>

              <div class="form-group">
                  <div class="col-md-6 col-md-offset-4">
                      <button type="submit" class="btn btn-primary add">
                          Add
                      </button>
                  </div>
              </div>
          </form>
        </div>
      </div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>