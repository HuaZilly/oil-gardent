
<div class="custom-form">
    
    <div class="form">

        <?php if ($_GET['submit'] === 'success') : ?>
            <h3 class="suc-title">Thank you!</h3>
            <p class="suc-text">
                Your message has been successfully sent. We will contact you very soon!
            </p>
        <?php else : ?>

        <h3>Contact Form</h3>

        <?php echo do_shortcode('[contact-form-7 id="2069" title="Contact form"]'); ?>


       
    <?php endif; ?>

    </div>

    <?php $contact_info = get_field('contact_info', 'option'); ?>
    <div class="contact-info">
        <p><?php echo $contact_info['description']; ?></p>
        <ul class="bold">
            <?php foreach($contact_info['contact_numbers'] as $cn): ?>
                <li><?php echo $cn['number'].' '. $cn['number_description']; ?></li>
            <?php endforeach; ?>
        </ul>
        <ul class="available-days">
            <?php foreach($contact_info['working_days'] as $wd): ?>
            <li><strong><?php echo $wd['days_range']; ?> </strong><br/> <?php echo $wd['open_hours']; ?></li>
            <?php endforeach; ?>
        </ul>
    </div>

</div>


