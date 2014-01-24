<section class="pages sample">
	<?php $this->assign('title', __('Sample')); ?>
	<?php $this->assign('description', 'A little sample page for testing'); ?>
	<?php $this->Html->addCrumb('Sample', '/pages/sample'); ?>
	<h1><?php echo __('Sample'); ?></h1>
	<article>
		<?php print $this->Address->beginn('asd'); ?>
		<?php
		print $this->Address->name(array(
				'legalName' => 'New York City Ballet'
				), array(
				'link' => $this->Html->url($this->request->here, true),
		));
		print $this->Address->founder(array(
				'givenName' => 'Jane',
				'additionalName' => 'Middle',
				'familyName' => 'Doh!'
		));
		print $this->Address->address(array(
				'streetAddress' => '20 Lincoln Center',
				'addressCountry' => 'USA',
				'addressLocality' => 'New York',
				'addressRegion' => 'NY',
				'postalCode' => '10023'
				), array(
				'format' => '%s<br />%s,%s %s'
		));

		print $this->Address->contact(array(
				'telephone' => '0126/123456',
				'faxNumber' => '0126/123457',
				'email' => 'ma.muster@example.com',
				'url' => 'http://www.some-example.de'
				), array(
//				'link' => false,
				'format' => '<span class="label">Telefon: </span>%s <br /><span class="label">Fax: </span>%s <br /><span class="label">Email: </span>%s<br /><span class="label">WWW: </span>%s'
				), array('class' => 'prop'));
		?>
		<?php print $this->Address->end(); ?>
		<!-- another way for organizations -->
		<?php print $this->Address->beginn('MyOrga'); ?>
		<?php
		print $this->Address->organization(array(
				'name' => array(
					'content' => array(
						'legalName' => 'An Organization of MyLand Inc.'
					),
					'options' => array(
						'link' => $this->Html->url($this->request->here, true)
					)
				),
				'founder' => array(
					'content' => array(
						'givenName' => 'Jane',
						'familyName' => 'Doh!'
					)
				),
				'address' => array(
					'content' => array(
						'streetAddress' => 'Mainstreet 1',
						'addressCountry' => 'DE',
						'postalCode' => '12345',
						'addressLocality' => 'ExVille',
						'addressRegion' => 'Region of EV'
					),
					'options' => array(
						'format' => '%s<br />%s-%s,%s %s'
					)
				),
				'contact' => array(
					'content' => array(
						'telephone' => '0126/123456',
						'faxNumber' => '0126/123457',
						'email' => 'ma.muster@example.com'
					),
					'options' => array(
						'format' => 'Telefon: %s <br /> Fax: %s <br /> Email: %s'
					),
					'itemoptions' => array('class' => 'prop')
				)
				), array(
				'class' => 'organization'
		));
		?>
		<?php print $this->Address->end(); ?>
	</article>
</section>