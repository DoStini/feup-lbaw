<?php $__env->startSection('content'); ?>
<form method="POST" action="<?php echo e(route('register')); ?>">
    <?php echo e(csrf_field()); ?>


    <label for="name">Name</label>
    <input id="name" type="text" name="name" value="<?php echo e(old('name')); ?>" required autofocus>
    <?php if($errors->has('name')): ?>
      <span class="error">
          <?php echo e($errors->first('name')); ?>

      </span>
    <?php endif; ?>

    <label for="email">E-Mail Address</label>
    <input id="email" type="email" name="email" value="<?php echo e(old('email')); ?>" required>
    <?php if($errors->has('email')): ?>
      <span class="error">
          <?php echo e($errors->first('email')); ?>

      </span>
    <?php endif; ?>

    <label for="password">Password</label>
    <input id="password" type="password" name="password" required>
    <?php if($errors->has('password')): ?>
      <span class="error">
          <?php echo e($errors->first('password')); ?>

      </span>
    <?php endif; ?>

    <label for="password-confirm">Confirm Password</label>
    <input id="password-confirm" type="password" name="password_confirmation" required>

    <button type="submit">
      Register
    </button>
    <a class="button button-outline" href="<?php echo e(route('login')); ?>">Login</a>
</form>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/nunoa/Documents/FEUP/THIRD_YEAR/LBAW/lbaw2116/template-laravel/resources/views/auth/register.blade.php ENDPATH**/ ?>