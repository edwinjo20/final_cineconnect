import { Component } from '@angular/core';
import { RouterModule } from '@angular/router';
import { FormsModule } from '@angular/forms';
import { CommonModule } from '@angular/common';

@Component({
  selector: 'app-root',
  standalone: true,
  template: `
    <nav>
      <a routerLink="/films">Films</a>
      <a *ngIf="!isLoggedIn" routerLink="/login">Login</a>
      <button *ngIf="isLoggedIn" (click)="logout()">Logout</button>
    </nav>
    <router-outlet></router-outlet>
  `,
  styleUrls: ['./app-root.component.css'],
  imports: [RouterModule, FormsModule, CommonModule],
})
export class AppRootComponent {
  isLoggedIn = false;

  ngOnInit(): void {
    this.checkLoginStatus();
  }

  checkLoginStatus(): void {
    const storedUser = localStorage.getItem('user');
    this.isLoggedIn = !!storedUser;
  }

  logout(): void {
    localStorage.removeItem('user');
    this.isLoggedIn = false;
  }
}
