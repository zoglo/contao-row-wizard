import { Application } from '@hotwired/stimulus';

import SimpleColumnWizard from './scripts/column-wizard-controller';
import Sortable from './scripts/sortable-controller';
import './styles/column-wizard.pcss';

window.Stimulus = Application.start();
Stimulus.register('simple-column-wizard', SimpleColumnWizard);
Stimulus.register('sortable', Sortable);
