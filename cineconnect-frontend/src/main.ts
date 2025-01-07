import { bootstrapApplication } from '@angular/platform-browser';
import { provideRouter } from '@angular/router';
import { importProvidersFrom } from '@angular/core';
import { HttpClientModule } from '@angular/common/http';
import { FormsModule } from '@angular/forms';

import { AppRootComponent } from './app/app-root/app-root.component';
import { FilmListComponent } from './app/components/film-list/film-list.component';
import { LoginComponent } from './app/components/login/login.component';

bootstrapApplication(AppRootComponent, {
  providers: [
    provideRouter([
      { path: '', component: FilmListComponent },
      { path: 'login', component: LoginComponent },
    ]),
    importProvidersFrom(HttpClientModule, FormsModule),
  ],
});
