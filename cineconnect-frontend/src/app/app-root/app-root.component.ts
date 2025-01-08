import { Component } from '@angular/core';
import { RouterModule } from '@angular/router';
import { FormsModule } from '@angular/forms';
import { CommonModule } from '@angular/common';

@Component({
  selector: 'app-root',
  standalone: true,
  templateUrl: './app-root.component.html',
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
